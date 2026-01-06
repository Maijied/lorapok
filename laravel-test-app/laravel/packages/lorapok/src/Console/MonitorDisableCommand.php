<?php
namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;

class MonitorDisableCommand extends Command
{
    protected $signature = 'monitor:disable';
    protected $description = 'Disable Lorapok Execution Monitor';

    public function handle()
    {
        $this->info('✅ Use config file to disable monitoring');
        $this->line('Edit config/execution-monitor.php and set enabled => false');
        return 0;
    }
}
