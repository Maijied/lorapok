<?php

namespace Lorapok\ExecutionMonitor\Analyzers;

class QueryPatternLibrary
{
    protected $patterns = [
        'select_all' => [
            'regex' => '/SELECT\s+\*\s+FROM/i',
            'tip' => 'Avoid using SELECT *. Specify only the columns you need to reduce memory usage and I/O.',
            'severity' => 'low'
        ],
        'missing_where' => [
            'regex' => '/^SELECT\s+.*FROM\s+([^\s]+)$/i',
            'tip' => 'This query seems to be fetching all rows without a WHERE clause. Consider adding limits or filters.',
            'severity' => 'medium'
        ],
        'leading_wildcard' => [
            'regex' => '/LIKE\s+[\'"]%.*[\'"]/i',
            'tip' => 'Leading wildcards in LIKE clauses prevent index usage. Consider using full-text search or a search engine.',
            'severity' => 'medium'
        ],
        'unoptimized_join' => [
            'regex' => '/JOIN\s+.*\s+ON\s+.*=.*(OR|NOT)/i',
            'tip' => 'Complex JOIN conditions with OR/NOT can be slow. Try to simplify or use separate queries.',
            'severity' => 'medium'
        ]
    ];

    public function analyze(string $sql): array
    {
        $tips = [];
        foreach ($this->patterns as $key => $data) {
            if (preg_match($data['regex'], $sql)) {
                $tips[] = [
                    'type' => $key,
                    'tip' => $data['tip'],
                    'severity' => $data['severity']
                ];
            }
        }
        return $tips;
    }
}
