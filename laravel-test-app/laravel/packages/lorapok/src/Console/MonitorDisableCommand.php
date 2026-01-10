<?php
namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;

class MonitorDisableCommand extends Command
{
    protected $signature = 'monitor:disable {feature? : The feature to disable (widget, routes, queries, functions)}';
    protected $description = 'Disable Lorapok Execution Monitor or specific features';

    public function handle()
    {
        $feature = $this->argument('feature');
        $settingsPath = storage_path('app/lorapok/settings.json');
        
        $settings = [];
        if (file_exists($settingsPath)) {
            $settings = json_decode(file_get_contents($settingsPath), true) ?? [];
        }

        if ($feature) {
            if (!in_array($feature, ['widget', 'routes', 'queries', 'functions'])) {
                $this->error("Unknown feature: $feature");
                return 1;
            }
            $settings["feature_{$feature}"] = false;
            $this->info("✅ Feature '{$feature}' disabled.");
        } else {
            $settings['enabled'] = false;
            $this->info('✅ Monitor disabled globally.');
        }

        if (!file_exists(dirname($settingsPath))) mkdir(dirname($settingsPath), 0755, true);
        file_put_contents($settingsPath, json_encode($settings, JSON_PRETTY_PRINT));
        
        $this->call('config:clear');
        return 0;
    }
}
