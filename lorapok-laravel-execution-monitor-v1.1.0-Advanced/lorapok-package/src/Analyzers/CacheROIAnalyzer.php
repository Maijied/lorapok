<?php

namespace Lorapok\ExecutionMonitor\Analyzers;

use Lorapok\ExecutionMonitor\Reporters\MonitorReporter;

class CacheROIAnalyzer
{
    public function analyze(array $report): array
    {
        $suggestions = [];
        $history = (new MonitorReporter())->getHistory();
        
        // 1. Detect Expensive Hot Routes (Response Caching)
        $routeROI = $this->analyzeRouteROI($history);
        $suggestions = array_merge($suggestions, $routeROI);

        // 2. Detect Expensive Queries (Query Caching)
        $queryROI = $this->analyzeQueryROI($report['queries'] ?? []);
        $suggestions = array_merge($suggestions, $queryROI);

        return $suggestions;
    }

    protected function analyzeRouteROI(array $history): array
    {
        if (empty($history)) return [];

        $routes = [];
        foreach ($history as $snapshot) {
            $path = $snapshot['route'];
            if (!isset($routes[$path])) {
                $routes[$path] = ['durations' => [], 'method' => $snapshot['method']];
            }
            $routes[$path]['durations'][] = $snapshot['duration'];
        }

        $suggestions = [];
        foreach ($routes as $path => $data) {
            $count = count($data['durations']);
            if ($count < 2) continue; // Need at least some history

            $avgDuration = array_sum($data['durations']) / $count;
            $avgMs = $avgDuration * 1000;

            // If average is > 500ms and hit multiple times
            if ($avgMs > 500) {
                $roi = round($avgMs * 0.9, 0); // Assume 90% savings
                $suggestions[] = [
                    'title' => "🚀 Response Caching Candidate",
                    'subject' => "{$data['method']} {$path}",
                    'roi' => "Estimated ROI: ~{$roi}ms saved per request",
                    'impact' => 'High',
                    'action' => "Route is hit repeatedly and average duration is slow ({$avgMs}ms). Suggest implementing Laravel Response Cache or manual Page Caching.",
                    'type' => 'route'
                ];
            }
        }

        return $suggestions;
    }

    protected function analyzeQueryROI(array $queries): array
    {
        $suggestions = [];
        $slowThreshold = config('execution-monitor.thresholds.query', 100);

        foreach ($queries as $q) {
            $time = $q['time'] ?? 0;
            if ($time > $slowThreshold) {
                $roi = round($time * 0.95, 0);
                $suggestions[] = [
                    'title' => "🗄️ Query Caching Candidate",
                    'subject' => substr($q['sql'], 0, 100) . '...',
                    'roi' => "Estimated ROI: ~{$roi}ms saved per hit",
                    'impact' => 'Medium',
                    'action' => "This query took {$time}ms. If data is read-heavy, use: Cache::remember('key', \$ttl, fn() => ...);",
                    'type' => 'query'
                ];
            }
        }

        return $suggestions;
    }
}

