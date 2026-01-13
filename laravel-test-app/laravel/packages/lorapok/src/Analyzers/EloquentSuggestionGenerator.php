<?php

namespace Lorapok\ExecutionMonitor\Analyzers;

class EloquentSuggestionGenerator
{
    public function generate(array $queries): array
    {
        $suggestions = [];
        $duplicates = [];

        foreach ($queries as $q) {
            $sql = $q['sql'];
            // Simplify SQL: SELECT * FROM posts WHERE user_id = 1 -> SELECT * FROM posts WHERE user_id = ?
            $pattern = preg_replace('/=\s*[\'"]?[\d\w-]+[\'"]?\s*$/i', '= ?', $sql);
            $duplicates[$pattern][] = $sql;
        }

        foreach ($duplicates as $pattern => $instances) {
            if (count($instances) > 3) {
                // Try to guess the relationship
                // SELECT * FROM `posts` WHERE `posts`.`user_id` = ?
                // OR SELECT * FROM posts WHERE user_id = ?
                if (preg_match('/FROM\s+[`"]?([^`"\s]+)[`"]?\s+WHERE\s+([^=]+)\s*=\s*\?/i', $pattern, $matches)) {
                    $table = $matches[1];
                    $whereClause = trim($matches[2]);
                    
                    // Extract column name: `posts`.`user_id` -> user_id
                    $column = $whereClause;
                    if (str_contains($column, '.')) {
                        $parts = explode('.', $column);
                        $column = end($parts);
                    }
                    $column = trim($column, '`" ');
                    
                    $relation = str_replace('_id', '', $column);
                    
                    $suggestions[] = [
                        'title' => "Possible N+1 detected on table '{$table}'",
                        'before' => "// Currently triggering " . count($instances) . " queries\n\$items = Model::all();\nforeach (\$items as \$item) {\n    \$item->{$relation}; // <--- Problem\n}",
                        'after' => "// Solution: Eager Load the relationship\n\$items = Model::with('{$relation}')->get();",
                        'impact' => "Could reduce " . count($instances) . " queries down to 2."
                    ];
                }
            }
        }

        return $suggestions;
    }
}
