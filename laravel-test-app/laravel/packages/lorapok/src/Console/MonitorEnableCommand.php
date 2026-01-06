<?php
namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;

class MonitorEnableCommand extends Command
{
    protected $signature = 'monitor:enable';
    protected $description = 'Enable Lorapok Execution Monitor';

    public function handle()
    {
        $this->info('✅ Use config file to enable monitoring');
        $this->line('Edit config/execution-monitor.php and set enabled => true');
        return 0;
    }
}
