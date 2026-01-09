<?php

namespace Lorapok\ExecutionMonitor\Analyzers;

class CacheROIAnalyzer
{
    public function analyze(array $queries): array
    {
        $suggestions = [];
        $slowThreshold = config('execution-monitor.thresholds.query', 100);

        foreach ($queries as $q) {
            if ($q['time'] > $slowThreshold) {
                $sql = $q['sql'];
                $savings = round($q['time'] * 0.95, 2); // Assume 95% savings with cache

                $suggestions[] = [
                    'type' => 'cache_opportunity',
                    'query' => $sql,
                    'time' => $q['time'],
                    'potential_savings' => $savings,
                    'tip' => "This query is slow ({$q['time']}ms). If the data doesn't change frequently, consider caching it: Cache::remember('key', 3600, fn() => ...);"
                ];
            }
        }

        return $suggestions;
    }
}
