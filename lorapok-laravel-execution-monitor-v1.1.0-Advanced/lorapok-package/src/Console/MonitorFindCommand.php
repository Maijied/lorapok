<?php

namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class MonitorFindCommand extends Command
{
    protected $signature = 'monitor:find {--contains= : Search string for fingerprint}';
    protected $description = 'Find performance snapshots by fingerprint';

    public function handle()
    {
        $history = Cache::get('lorapok_history', []);
        $query = $this->option('contains');
        $generator = new \Lorapok\ExecutionMonitor\Services\FingerprintGenerator();

        if (empty($history)) {
            $this->warn('No history found.');
            return;
        }

        $this->info('Searching history...');

        foreach ($history as $item) {
            $report = [
                'request' => [
                    'method' => $item['method'],
                    'path' => $item['route'],
                    'duration' => $item['duration']
                ],
                'total_queries' => $item['queries'],
                'slow_queries_count' => $item['slow_queries'] ?? 0,
                'n1_queries_count' => $item['n1_queries'] ?? 0,
                'memory_peak' => $item['memory'] ?? '0MB',
            ];

            $fingerprint = $generator->generate($report);
            
            if ($query && !str_contains(strtolower($fingerprint), strtolower($query))) {
                continue;
            }

            $this->line("<fg=magenta>#</> {$fingerprint} <fg=gray>({$item['timestamp']})</>");
        }
    }
}
