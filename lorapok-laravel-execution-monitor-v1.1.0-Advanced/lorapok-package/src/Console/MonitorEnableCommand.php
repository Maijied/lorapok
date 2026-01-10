<?php
namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;

class MonitorEnableCommand extends Command
{
    protected $signature = 'monitor:enable {feature? : The feature to enable (widget, routes, queries, functions)}';
    protected $description = 'Enable Lorapok Execution Monitor or specific features';

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
            $settings["feature_{$feature}"] = true;
            $this->info("✅ Feature '{$feature}' enabled.");
        } else {
            $settings['enabled'] = true;
            $this->info('✅ Monitor enabled globally.');
        }

        if (!file_exists(dirname($settingsPath))) mkdir(dirname($settingsPath), 0755, true);
        file_put_contents($settingsPath, json_encode($settings, JSON_PRETTY_PRINT));
        
        $this->call('config:clear');
        return 0;
    }
}
