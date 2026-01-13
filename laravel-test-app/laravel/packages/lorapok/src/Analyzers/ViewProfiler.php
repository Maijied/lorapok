<?php

namespace Lorapok\ExecutionMonitor\Analyzers;

use Illuminate\Support\Facades\Event;
use Illuminate\View\View;

class ViewProfiler
{
    protected $views = [];

    public function startTracking()
    {
        Event::listen('composing:*', function ($view, $data = null) {
            if ($data && isset($data[0]) && $data[0] instanceof View) {
                $viewName = $data[0]->getName();
                $this->views[$viewName] = [
                    'name' => $viewName,
                    'start' => microtime(true),
                    'path' => $data[0]->getPath()
                ];
            }
        });

        Event::listen('creating:*', function ($view, $data = null) {
             // Optional: track creation
        });
    }

    public function recordRender($viewName, $duration)
    {
        if (isset($this->views[$viewName])) {
            $this->views[$viewName]['duration'] = $duration;
        } else {
            $this->views[$viewName] = [
                'name' => $viewName,
                'duration' => $duration
            ];
        }
    }

    public function getViews(): array
    {
        return array_values($this->views);
    }
}
