<?php

namespace Lorapok\ExecutionMonitor\Reporters;

class TimelineReporter
{
    protected $events = [];
    protected $start;

    public function __construct()
    {
        $this->start = defined('LARAVEL_START') ? LARAVEL_START : microtime(true);
        $this->record('boot', $this->start);
    }

    public function record(string $name, float $timestamp = null)
    {
        $this->events[] = [
            'name' => $name,
            'time' => $timestamp ?? microtime(true),
        ];
    }

    public function getTimeline(): array
    {
        $lastTime = $this->start;
        $timeline = [];

        foreach ($this->events as $event) {
            $duration = ($event['time'] - $lastTime) * 1000; // ms
            $timeline[] = [
                'name' => $event['name'],
                'duration' => round($duration, 2),
                'timestamp' => round(($event['time'] - $this->start) * 1000, 2),
            ];
            $lastTime = $event['time'];
        }

        return $timeline;
    }
}
