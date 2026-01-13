<?php

namespace Lorapok\ExecutionMonitor\Services;

class FingerprintGenerator
{
    public function generate(array $report): string
    {
        $method = $report['request']['method'] ?? 'N/A';
        $path = $report['request']['path'] ?? 'N/A';
        $duration = round(($report['request']['duration'] ?? 0) * 1000);
        $queries = $report['total_queries'] ?? 0;
        $slowQueries = $report['slow_queries_count'] ?? 0;
        $n1 = $report['n1_queries_count'] ?? 0;
        $memory = $report['memory_peak'] ?? '0MB';

        return "{$method}:/{$path} | {$duration}ms | q={$queries} | slowQ={$slowQueries} | mem={$memory} | n1={$n1}";
    }
}
