<?php
namespace Lorapok\ExecutionMonitor\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectMonitorWidget
{
    public function handle(Request $request, Closure $next)
    {
        \Log::debug('Lorapok Widget Middleware: Checking request ' . $request->path());

        if (app()->bound('execution-monitor') && !app('execution-monitor')->isEnabled()) {
            \Log::debug('Lorapok Widget Middleware: Monitor disabled');
            return $next($request);
        }
        
        if ($request->expectsJson() || $request->ajax()) {
            \Log::debug('Lorapok Widget Middleware: AJAX/JSON request');
            return $next($request);
        }

        $response = $next($request);

                        if ($this->isHtmlResponse($response)) {

                            $content = $response->getContent();

                            

                            // Avoid double injection

                            if (str_contains($content, 'id="execution-monitor-widget"')) {

                                \Log::debug('Lorapok Widget Middleware: Widget already present, skipping injection');

                                return $response;

                            }

                

                            \Log::debug('Lorapok Widget Middleware: Injecting into HTML response');

                            $widget = view('execution-monitor::widget')->render();

                            

                            // Find the last </body> tag to avoid replacing code snippets in error pages

                            $pos = strripos($content, '</body>');

                            if ($pos !== false) {

                                $content = substr_replace($content, $widget . '</body>', $pos, strlen('</body>'));

                                $response->setContent($content);

                            } else {

                                // Fallback: just append if no body tag found (rare for HTML responses)

                                $response->setContent($content . $widget);

                            }

                        }

                

         else {
            \Log::debug('Lorapok Widget Middleware: Not an HTML response');
        }

        return $response;
    }

    protected function isHtmlResponse($response)
    {
        return $response instanceof \Illuminate\Http\Response &&
               str_contains($response->headers->get('Content-Type', ''), 'text/html');
    }
}
