<?php

namespace Lorapok\ExecutionMonitor\Reporters;

class NarrativeGenerator
{
    public function generate(array $report): string
    {
        $duration = round(($report['request']['duration'] ?? 0) * 1000);
        $queries = $report['total_queries'] ?? 0;
        $queryTime = round($report['total_query_time'] ?? 0);
        $memory = $report['memory_peak'] ?? '0MB';

        $narrative = "This request took **{$duration}ms** to complete. ";
        
        if ($queries > 0) {
            $queryPercent = $duration > 0 ? round(($queryTime / $duration) * 100) : 0;
            $narrative .= "It spent **{$queryTime}ms** ({$queryPercent}%) executing **{$queries} queries**. ";
        }

        $narrative .= "Peak memory usage was **{$memory}**. ";

        if ($queries > 20) {
            $narrative .= "The high number of queries is likely the main bottleneck. ";
        } elseif ($queryTime > ($duration * 0.7)) {
            $narrative .= "Database performance is heavily impacting this request. ";
        } elseif ($duration > 1000) {
            $narrative .= "This request is quite slow. Consider optimizing the controller logic or caching the response. ";
        } else {
            $narrative .= "Overall, the performance looks solid! 🐛";
        }

        return $narrative;
    }
}
