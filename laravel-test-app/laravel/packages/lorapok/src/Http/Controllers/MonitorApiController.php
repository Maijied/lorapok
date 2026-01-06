<?php
namespace Lorapok\ExecutionMonitor\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

class MonitorApiController extends Controller
{
    public function getData()
    {
        // Get stored monitor data from cache
        $report = Cache::get('lorapok_latest_monitor', null);

        // Fallback: if cache is empty, try to read from the monitor singleton (useful in dev)
        if (!$report && app()->bound('execution-monitor')) {
            try {
                $report = app('execution-monitor')->getReport();
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
        
        // Format route duration for display and normalize `current_route` to a display string
        if (isset($report['current_route']) && is_array($report['current_route']) && isset($report['current_route']['duration'])) {
            $duration = $report['current_route']['duration'];
            $report['current_route']['duration_ms'] = round($duration * 1000, 2);
            $report['current_route']['duration_formatted'] = $this->formatDuration($duration);
        }

        // Normalize current_route for simple views that expect a string
        if (isset($report['current_route']) && is_array($report['current_route'])) {
            $report['current_route'] = $report['current_route']['path'] ?? 'N/A';
        } else {
            $report['current_route'] = (string) ($report['current_route'] ?? 'N/A');
        }

        // Expose memory peak as a top-level key for legacy views
        $report['memory_peak'] = $report['memory']['peak'] ?? null;
        
        $report['alerts'] = $this->checkThresholds($report);
        
        return response()->json($report);
    }
    
    protected function checkThresholds($report)
    {
        $alerts = [];
        
        // Check route duration threshold
        if (isset($report['current_route']) && is_array($report['current_route']) && isset($report['current_route']['duration'])) {
            $duration = $report['current_route']['duration'];
            $threshold = config('execution-monitor.thresholds.route_time', 1.0);

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
    
    protected function formatBytes($bytes)
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        return round($bytes / 1024, 2) . ' KB';
    }
}
