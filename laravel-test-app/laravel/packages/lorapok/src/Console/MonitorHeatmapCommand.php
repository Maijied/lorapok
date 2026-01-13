<?php

namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class MonitorHeatmapCommand extends Command
{
    protected $signature = 'monitor:heatmap {--export= : Export to file (json|csv)}';
    protected $description = 'Generate a performance heatmap of routes';

    public function handle()
    {
        $history = Cache::get('lorapok_history', []);
        if (empty($history)) {
            $this->warn('No history found to generate heatmap.');
            return;
        }

        $routes = [];
        foreach ($history as $item) {
            $name = "{$item['method']} {$item['route']}";
            if (!isset($routes[$name])) {
                $routes[$name] = [
                    'route' => $item['route'],
                    'method' => $item['method'],
                    'durations' => [],
                    'queries' => [],
                    'count' => 0
                ];
            }
            $routes[$name]['durations'][] = $item['duration'] * 1000;
            $routes[$name]['queries'][] = $item['queries'];
            $routes[$name]['count']++;
        }

        $heatmap = [];
        foreach ($routes as $name => $data) {
            sort($data['durations']);
            $count = count($data['durations']);
            $p95Index = min($count - 1, floor($count * 0.95));
            
            $heatmap[] = [
                'route' => $data['route'],
                'method' => $data['method'],
                'avg_duration' => round(array_sum($data['durations']) / $count, 2),
                'p95_duration' => round($data['durations'][$p95Index], 2),
                'max_duration' => round(max($data['durations']), 2),
                'avg_queries' => round(array_sum($data['queries']) / $count, 1),
                'call_count' => $data['count']
            ];
        }

        // Sort by p95 duration desc
        usort($heatmap, fn($a, $b) => $b['p95_duration'] <=> $a['p95_duration']);

        $export = $this->option('export');
        if ($export) {
            $this->export($heatmap, $export);
            return;
        }

        $this->table(
            ['Method', 'Route', 'Avg (ms)', 'p95 (ms)', 'Max (ms)', 'Calls'],
            array_map(fn($h) => [
                $h['method'], $h['route'], $h['avg_duration'], $h['p95_duration'], $h['max_duration'], $h['call_count']
            ], $heatmap)
        );
    }

    protected function export(array $heatmap, string $filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ($ext === 'json') {
            file_put_contents($filename, json_encode($heatmap, JSON_PRETTY_PRINT));
        } elseif ($ext === 'csv') {
            $fp = fopen($filename, 'w');
            fputcsv($fp, array_keys($heatmap[0]));
            foreach ($heatmap as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);
        }
        $this->info("Exported heatmap to {$filename}");
    }
}
