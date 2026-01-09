<?php

namespace Lorapok\ExecutionMonitor\Services;

class PerformanceBudget
{
    public function check(array $report): array
    {
        $path = $report['request']['path'] ?? '/';
        $budgets = config('execution-monitor.budgets', []);
        
        $violated = [];
        
        // Find matching budget pattern
        $budget = null;
        foreach ($budgets as $pattern => $limit) {
            if (fnmatch($pattern, $path) || $pattern === $path) {
                $budget = $limit;
                break;
            }
        }

        if (!$budget) return [];

        $duration = ($report['request']['duration'] ?? 0) * 1000;
        if (isset($budget['duration']) && $duration > $budget['duration']) {
            $violated[] = "Duration budget exceeded: {$duration}ms > {$budget['duration']}ms";
        }

        $queries = $report['total_queries'] ?? 0;
        if (isset($budget['queries']) && $queries > $budget['queries']) {
            $violated[] = "Query budget exceeded: {$queries} > {$budget['queries']}";
        }

        $memory = (float) ($report['memory_peak'] ?? 0);
        if (isset($budget['memory']) && $memory > $budget['memory']) {
            $violated[] = "Memory budget exceeded: {$memory}MB > {$budget['memory']}MB";
        }

        return $violated;
    }
}
