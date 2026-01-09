<?php
namespace Lorapok\ExecutionMonitor\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MonitorApiController extends Controller
{
    public function getData()
    {
        // Get stored monitor data from cache
        $report = Cache::get('lorapok_latest_monitor', null);

        // Fallback: if cache is empty, try to read from the monitor singleton (useful in dev)
        if (!$report && app()->bound('execution-monitor')) {
            try {
                $report = app('execution-monitor')->getReport(false);
            } catch (\Throwable $e) {
                $report = null;
            }
        }

        // Provide sensible defaults when no report exists
        if (!$report) {
            $report = [
                'timers' => [],
                'queries' => [],
                'routes' => [],
                'total_queries' => 0,
                'total_query_time' => 0,
                'current_route' => [
                    'path' => 'No data',
                    'method' => 'GET',
                    'duration' => 0
                ]
            ];
        }
        
        // Add memory info (always present)
        $report['memory'] = [
            'current' => $this->formatBytes(memory_get_usage(true)),
            'peak' => $this->formatBytes(memory_get_peak_usage(true)),
        ];
        
        // Expose memory peak as a top-level key for legacy views
        $report['memory_peak'] = $report['memory']['peak'] ?? null;
        
        $report['alerts'] = $this->checkThresholds($report);

        // Normalize current_route for simple views that expect a string
        if (isset($report['current_route']) && is_array($report['current_route'])) {
            $report['current_route'] = $report['current_route']['path'] ?? 'N/A';
        } else {
            $report['current_route'] = (string) ($report['current_route'] ?? 'N/A');
        }
        
        // Merge persistent settings
        $report['settings'] = $this->getSettings();

        // System Information
        $report['system_info'] = [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'environment' => app()->environment(),
            'database' => $this->getDatabaseInfo(),
            'monitor_status' => app()->bound('execution-monitor') && app('execution-monitor')->isEnabled() ? '✅ Active' : '❌ Disabled',
            'widget_status' => '🟣 Loaded',
            'git_branch' => $this->getGitBranch(),
        ];
        
        return response()->json($report);
    }

    protected function getDatabaseInfo()
    {
        try {
            $connection = \DB::connection();
            $driver = $connection->getDriverName();
            $version = $connection->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION);
            return ucfirst($driver) . ' ' . $version;
        } catch (\Throwable $e) {
            return 'N/A';
        }
    }

    protected function getGitBranch()
    {
        try {
            // Try shell command first
            $branch = shell_exec('git rev-parse --abbrev-ref HEAD 2>/dev/null');
            if ($branch) return trim($branch);

            // Fallback: try reading .git/HEAD manually (if mapped)
            $headPath = base_path('.git/HEAD');
            if (file_exists($headPath)) {
                $head = file_get_contents($headPath);
                if (preg_match('/ref: refs\/heads\/(.*)/', $head, $matches)) {
                    return trim($matches[1]);
                }
            }
            
            // Try parent directory (common in Docker if base_path is a subdir)
            $headPath = base_path('../.git/HEAD');
            if (file_exists($headPath)) {
                $head = file_get_contents($headPath);
                if (preg_match('/ref: refs\/heads\/(.*)/', $head, $matches)) {
                    return trim($matches[1]);
                }
            }

            return 'N/A';
        } catch (\Throwable $e) {
            return 'N/A';
        }
    }
    
    protected function checkThresholds($report)
    {
        $alerts = [];
        
        // Check route duration threshold
        if (isset($report['current_route']) && is_array($report['current_route']) && isset($report['current_route']['duration'])) {
            $duration = $report['current_route']['duration'];
            $threshold = config('execution-monitor.thresholds.route', 1000) / 1000;

            if ($duration > $threshold) {
                $alerts[] = [
                    'type' => 'slow_route',
                    'severity' => 'error',
                    'message' => sprintf(
                        '⚠️ Slow route detected! Took %.0fms (threshold: %.0fms)',
                        $duration * 1000,
                        $threshold * 1000
                    )
                ];
            }
        }
        
        // Check query count
        $queryCount = $report['total_queries'] ?? 0;
        $queryThreshold = config('execution-monitor.thresholds.query_count', 50);
        
        if ($queryCount > $queryThreshold) {
            $alerts[] = [
                'type' => 'too_many_queries',
                'severity' => 'error',
                'message' => sprintf(
                    '⚠️ Too many queries: %d (threshold: %d)',
                    $queryCount,
                    $queryThreshold
                )
            ];
        }
        
        return $alerts;
    }
    
    protected function formatDuration($seconds)
    {
        $ms = $seconds * 1000;
        if ($ms < 100) return '🟢 Fast';
        if ($ms < 1000) return '🟡 Normal';
        return '🔴 Slow';
    }

    public function saveSettings(Request $request)
    {
        try {
            $data = $request->validate([
                'discord_webhook' => 'nullable|url',
                'discord_enabled' => 'boolean',
                'slack_webhook' => 'nullable|string', 
                'slack_channel' => 'nullable|string',
                'slack_enabled' => 'boolean',
                'mail_to' => 'nullable|email',
                'mail_enabled' => 'boolean',
                'mail_host' => 'nullable|string',
                'mail_port' => 'nullable|numeric',
                'mail_username' => 'nullable|string',
                'mail_password' => 'nullable|string',
                'mail_encryption' => 'nullable|string',
                'mail_from_address' => 'nullable|email',
                'rate_limit_minutes' => 'nullable|numeric|min:1|max:1440',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Lorapok Settings Validation Failed:', $e->errors());
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        $path = storage_path('app/lorapok/settings.json');
        
        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        // Read existing settings
        $existing = [];
        if (File::exists($path)) {
            $existing = json_decode(File::get($path), true) ?? [];
        }

        // Merge
        $merged = array_merge($existing, array_filter($data, function($value) {
            return !is_null($value) && $value !== '';
        }));

        File::put($path, json_encode($merged, JSON_PRETTY_PRINT));

        return response()->json(['success' => true]);
    }

    public function testSettings(Request $request)
    {
        try {
            $data = $request->validate([
                'discord_webhook' => 'nullable|url',
                'discord_enabled' => 'boolean',
                'slack_webhook' => 'nullable|string',
                'slack_channel' => 'nullable|string',
                'slack_enabled' => 'boolean',
                'mail_to' => 'nullable|email',
                'mail_enabled' => 'boolean',
                'mail_host' => 'nullable|string',
                'mail_port' => 'nullable|numeric',
                'mail_username' => 'nullable|string',
                'mail_password' => 'nullable|string',
                'mail_encryption' => 'nullable|string',
                'mail_from_address' => 'nullable|email',
                'rate_limit_minutes' => 'nullable|numeric|min:1|max:1440',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Lorapok Test Settings Validation Failed:', $e->errors());
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        }

        try {
             if (app()->bound('execution-monitor')) {
                app('execution-monitor')->sendTestAlert($data);
                return response()->json(['success' => true]);
             }
        } catch (\Throwable $e) {
            \Log::error('Lorapok Test Alert Failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => false, 'error' => 'Monitor not bound'], 500);
    }

    protected function getSettings()
    {
        $path = storage_path('app/lorapok/settings.json');
        
        $settings = [];
        if (File::exists($path)) {
            $settings = json_decode(File::get($path), true);
        }

        return array_merge([
            'discord_webhook' => config('execution-monitor.notifications.discord.webhook_url'),
            'discord_enabled' => config('execution-monitor.notifications.discord.enabled', false),
            'slack_webhook' => config('execution-monitor.notifications.slack.webhook_url'),
            'slack_enabled' => config('execution-monitor.notifications.slack.enabled', false),
            'mail_to' => config('execution-monitor.notifications.mail.to'),
            'mail_enabled' => config('execution-monitor.notifications.mail.enabled', false),
            'rate_limit_minutes' => 30, // Default to 30 as per user request
        ], $settings);
    }

    protected function formatBytes($bytes)
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        return round($bytes / 1024, 2) . ' KB';
    }
}
