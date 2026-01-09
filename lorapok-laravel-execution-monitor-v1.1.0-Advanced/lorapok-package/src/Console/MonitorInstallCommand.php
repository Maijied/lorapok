<?php
namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;

class MonitorInstallCommand extends Command
{
    protected $signature = 'monitor:install {--force}';
    protected $description = 'Install Lorapok Execution Monitor';

    public function handle()
    {
        $this->info('🚀 Installing Lorapok Execution Monitor...');
        $this->call('vendor:publish', ['--tag' => 'lorapok-config', '--force' => $this->option('force')]);
        $this->call('vendor:publish', ['--tag' => 'lorapok-views', '--force' => $this->option('force')]);
        $this->info('✅ Installation completed!');
        $this->call('monitor:status');
        return 0;
    }
}
