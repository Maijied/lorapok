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

        // Listen for queries during this request
        $queryCaptured = 0;
        DB::listen(function ($query) use (&$queryCaptured) {
            app('execution-monitor')->logQuery($query->sql, $query->time);
            $queryCaptured++;
        });

        $start = microtime(true);
        $path = $request->path();
        app('execution-monitor')->startRoute($path);
        
        try {
            $response = $next($request);
        } catch (\Throwable $e) {
            // Capture exception
            app('execution-monitor')->setException($e);
            
            // End route timing (approximate)
            $duration = microtime(true) - $start;
            app('execution-monitor')->endRoute($path, $duration);
            
            app('execution-monitor')->setRequestData([
                'path' => $path,
                'method' => $request->method(),
                'duration' => $duration,
                'status' => 500,
            ]);

            // Persist report immediately before crashing
            $this->persistReport($request, $path, $duration, $queryCaptured);
            
            throw $e;
        }
        
        $duration = microtime(true) - $start;
        app('execution-monitor')->endRoute($path, $duration);
        
        app('execution-monitor')->setRequestData([
            'path' => $path,
            'method' => $request->method(),
            'duration' => $duration,
            'status' => $response->getStatusCode(),
        ]);

        $this->persistReport($request, $path, $duration, $queryCaptured);

        if (config('execution-monitor.add_header')) {
            $response->headers->set('X-Execution-Time', round($duration * 1000, 2) . 'ms');
        }

        return $response;
    }

    protected function persistReport($request, $path, $duration, $queryCaptured)
    {
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
            'captured' => $queryCaptured,
            'has_exception' => !empty($report['last_exception'])
        ]);
        
        // Store in cache for 5 minutes
        Cache::put('lorapok_latest_monitor', $report, 300);
        
        // Check thresholds and send alerts if needed
        try {
            if (app()->bound('execution-monitor')) {
                app('execution-monitor')->checkThresholds();
            }
        } catch (\Throwable $e) {
            Log::error('Lorapok: checkThresholds failed - ' . $e->getMessage());
        }
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
