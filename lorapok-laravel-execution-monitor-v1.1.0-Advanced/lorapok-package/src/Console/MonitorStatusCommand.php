<?php
namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class MonitorStatusCommand extends Command
{
    protected $signature = 'monitor:status';
    protected $description = 'Check Lorapok Execution Monitor status';

    public function handle()
    {
        $this->info('📊 Lorapok Execution Monitor Status');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $env = App::environment();
        $this->line("🌍 Environment: <fg=green>{$env}</>");
        $enabled = config('execution-monitor.auto_detect', true) ? 
            in_array($env, config('execution-monitor.allowed_environments', [])) : 
            config('execution-monitor.enabled', false);
        $status = $enabled ? '<fg=green>✓ ENABLED</>' : '<fg=red>✗ DISABLED</>';
        $this->line("📈 Status: {$status}");
        return 0;
    }
}
