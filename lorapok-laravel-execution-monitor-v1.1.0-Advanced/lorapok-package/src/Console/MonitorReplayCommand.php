<?php

namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Lorapok\ExecutionMonitor\Services\SnapshotService;

class MonitorReplayCommand extends Command
{
    protected $signature = 'monitor:replay {id? : The snapshot ID}';
    protected $description = 'Replay or view a captured slow request snapshot';

    public function handle()
    {
        $snapshots = Cache::get('lorapok_snapshots', []);
        if (empty($snapshots)) {
            $this->warn('No snapshots found.');
            return;
        }

        $id = $this->argument('id');
        if (!$id) {
            $this->info('Available Snapshots:');
            $this->table(
                ['ID', 'At', 'Method', 'URL', 'Duration (ms)'],
                array_map(fn($s) => [
                    $s['id'], $s['timestamp'], $s['method'], $s['url'], round($s['duration'] * 1000)
                ], $snapshots)
            );
            return;
        }

        $snapshot = collect($snapshots)->firstWhere('id', $id);
        if (!$snapshot) {
            $this->error("Snapshot {$id} not found.");
            return;
        }

        $this->info("Snapshot: {$id}");
        $this->line("URL: {$snapshot['url']}");
        $this->line("At:  {$snapshot['timestamp']}");
        
        $service = new SnapshotService();
        $curl = $service->generateCurl($snapshot);

        $this->newLine();
        $this->info("Reproduce with cURL:");
        $this->line($curl);
    }
}
