<?php
namespace Lorapok\ExecutionMonitor\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TrackExecutionTime
{
    public function handle(Request $request, Closure $next)
    {
        // Skip monitoring for API endpoint itself
        if ($request->is('execution-monitor/api/*')) {
            return $next($request);
        }

        if (!$this->shouldEnable()) {
            return $next($request);
        }

        Log::info('Lorapok: ✅ Tracking request - ' . $request->path());

        // Reset monitor data for new request
        app('execution-monitor')->reset();

        $start = microtime(true);
        $path = $request->path();
        app('execution-monitor')->startRoute($path);
        
        app('execution-monitor')->recordTimeline('controller');
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        app('execution-monitor')->endRoute($path, $duration);

        // Capture Request/Response Profiling
        $requestSize = strlen($request->getContent());
        foreach($request->headers->all() as $name => $values) {
            foreach($values as $value) {
                $requestSize += strlen($name) + strlen((string)$value) + 4;
            }
        }
        
        $responseSize = 0;
        if (method_exists($response, 'getContent')) {
            $responseSize = strlen($response->getContent());
        }

        app('execution-monitor')->setRequestData([
            'method' => $request->method(),
            'path' => $request->path(),
            'status' => $response->getStatusCode(),
            'request_size' => $requestSize,
            'response_size' => $responseSize,
            'duration' => $duration,
        ]);
        
        // Store monitor data in cache
        $report = app('execution-monitor')->getReport();
        $report['current_route'] = [
            'path' => $path,
            'method' => $request->method(),
            'duration' => $duration,
        ];
        $report['timestamp'] = now()->toDateTimeString();
        
        Log::info('Lorapok: 💾 Storing data for ' . $path, [
            'duration_ms' => round($duration * 1000, 2),
            'queries' => count($report['queries'] ?? []),
        ]);
        
        // Store in cache for 5 minutes
        Cache::put('lorapok_latest_monitor', $report, 300);

        // Record snapshot for history
        try {
            (new \Lorapok\ExecutionMonitor\MonitorReporter())->recordSnapshot($report);
            (new \Lorapok\ExecutionMonitor\Services\AchievementTracker())->track($report);

            // Capture snapshot if slow
            if ($duration * 1000 > config('execution-monitor.thresholds.route', 1000)) {
                (new \Lorapok\ExecutionMonitor\Services\SnapshotService())->capture($report);
            }

        } catch (\Throwable $e) {
            Log::error('Lorapok: history/achievement recording failed - ' . $e->getMessage());
        }

        // Budget Guardrails (outside recording try-catch)
        if (app()->environment('local') && config('execution-monitor.budgets_enabled', true)) {
            $violations = $report['budget_violations'] ?? [];
            if (!empty($violations) && config('execution-monitor.stop_on_violation', false)) {
                throw new \Exception("Performance Budget Violated: " . implode(', ', $violations));
            }
        }

        // Check thresholds and send alerts if needed
        try {
            if (app()->bound('execution-monitor')) {
                app('execution-monitor')->checkThresholds();
            }
        } catch (\Throwable $e) {
            Log::error('Lorapok: checkThresholds failed - ' . $e->getMessage());
        }
        if (config('execution-monitor.add_header')) {
            $response->headers->set('X-Execution-Time', round($duration * 1000, 2) . 'ms');
        }

        return $response;
    }

    protected function shouldEnable(): bool
    {
        if (config('execution-monitor.auto_detect', true)) {
            return $this->autoDetectEnvironment();
        }
        return config('execution-monitor.enabled', false);
    }

    protected function autoDetectEnvironment(): bool
    {
        $currentEnv = app()->environment();
        $allowedEnvs = config('execution-monitor.allowed_environments', ['local','development','dev','testing','staging']);
        return in_array($currentEnv, $allowedEnvs);
    }
}
