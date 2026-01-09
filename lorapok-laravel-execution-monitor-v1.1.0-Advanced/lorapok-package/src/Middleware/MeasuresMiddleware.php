<?php

namespace Lorapok\ExecutionMonitor\Middleware;

use Closure;

trait MeasuresMiddleware
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);
        $duration = microtime(true) - $start;

        if (app()->bound('execution-monitor')) {
            app('execution-monitor')->logMiddleware(static::class, $duration);
        }

        return $response;
    }
}
