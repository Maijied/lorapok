<?php
namespace Lorapok\ExecutionMonitor\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectMonitorWidget
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('execution-monitor.enabled', false)) return $next($request);
        if ($request->expectsJson() || $request->ajax()) return $next($request);
        $response = $next($request);
        if ($this->isHtmlResponse($response)) {
            $content = $response->getContent();
            $widget = view('execution-monitor::widget')->render();
            $content = str_replace('</body>', $widget . '</body>', $content);
            $response->setContent($content);
        }
        return $response;
    }

    protected function isHtmlResponse($response)
    {
        return $response instanceof \Illuminate\Http\Response &&
               str_contains($response->headers->get('Content-Type', ''), 'text/html');
    }
}
