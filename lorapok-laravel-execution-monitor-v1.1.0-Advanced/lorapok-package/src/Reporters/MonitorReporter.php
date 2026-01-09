<?php

namespace Lorapok\ExecutionMonitor\Reporters;

use Illuminate\Support\Facades\Cache;

class MonitorReporter
{
    protected $cacheKey = 'lorapok_history';
    protected $limit = 50;

    public function recordSnapshot(array $report)
    {
        $history = Cache::get($this->cacheKey, []);
        
        $snapshot = [
            'timestamp' => now()->toDateTimeString(),
            'route' => $report['request']['path'] ?? 'N/A',
            'method' => $report['request']['method'] ?? 'GET',
            'duration' => $report['request']['duration'] ?? 0,
            'queries' => $report['total_queries'] ?? 0,
            'status' => $report['request']['status'] ?? 200,
            'slow_queries' => $report['slow_queries_count'] ?? 0,
            'n1_queries' => $report['n1_queries_count'] ?? 0,
            'memory' => $report['memory_peak'] ?? '0MB',
        ];

        array_unshift($history, $snapshot);
        $history = array_slice($history, 0, $this->limit);

        Cache::forever($this->cacheKey, $history);
    }

    public function getHistory(): array
    {
        return Cache::get($this->cacheKey, []);
    }

    public function getSlowestRoutes(int $limit = 5): array
    {
        $history = $this->getHistory();
        usort($history, function ($a, $b) {
            return $b['duration'] <=> $a['duration'];
        });

        return array_slice($history, 0, $limit);
    }

    public function getStats(): array
    {
        $history = $this->getHistory();
        if (empty($history)) {
            return [
                'avg_duration' => 0,
                'avg_queries' => 0,
                'count' => 0
            ];
        }

        $totalDuration = array_sum(array_column($history, 'duration'));
        $totalQueries = array_sum(array_column($history, 'queries'));
        $count = count($history);

        return [
            'avg_duration' => round(($totalDuration / $count) * 1000, 2),
            'avg_queries' => round($totalQueries / $count, 1),
            'count' => $count
        ];
    }
}
