<?php

namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MonitorExportCommand extends Command
{
    protected $signature = 'monitor:export {--disk=local : Storage disk to use} {--filename=report.json : Filename}';
    protected $description = 'Export latest performance report to storage';

    public function handle()
    {
        $report = Cache::get('lorapok_latest_monitor');
        if (!$report) {
            $this->error('No recent report found to export.');
            return;
        }

        $disk = $this->option('disk');
        $filename = $this->option('filename');

        try {
            Storage::disk($disk)->put($filename, json_encode($report, JSON_PRETTY_PRINT));
            $this->info("Successfully exported report to {$disk} disk as {$filename}");
        } catch (\Throwable $e) {
            $this->error("Export failed: " . $e->getMessage());
        }
    }
}
