<?php
if (!function_exists('monitor')) {
    function monitor(?string $name = null, ?callable $callback = null)
    {
        $monitor = app()->bound('execution-monitor') ? app('execution-monitor') : new class {
            public function __call($method, $parameters) { return $this; }
            public function start($name) { return $this; }
            public function end($name) { return 0; }
            public function measure($name, $callback) { return $callback(); }
        };
        if ($name && $callback) return $monitor->measure($name, $callback);
        return $monitor;
    }
}

if (!function_exists('execution_monitor_enabled')) {
    function execution_monitor_enabled(?string $feature = null): bool
    {
        if (!config('execution-monitor.enabled', false)) return false;
        if ($feature) return config("execution-monitor.features.{$feature}", false);
        return true;
    }
}
