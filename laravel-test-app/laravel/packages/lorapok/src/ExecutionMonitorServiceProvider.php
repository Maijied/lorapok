<?php
namespace Lorapok\ExecutionMonitor;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ExecutionMonitorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/execution-monitor.php', 'execution-monitor');
        
        $this->app->singleton('execution-monitor', function ($app) {
            return new Monitor();
        });
        $this->app->alias('execution-monitor', Monitor::class);
        
        // Register DB query listener EARLY in register phase
        if ($this->shouldEnable() && $this->isFeatureEnabled('queries')) {
            $this->trackDatabaseQueriesEarly();
        }
    }

    public function boot()
    {
        // ALWAYS load views - regardless of enabled status
        $this->loadViewsFrom(__DIR__.'/resources/views', 'execution-monitor');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        
        $this->publishes([
            __DIR__.'/config/execution-monitor.php' => config_path('execution-monitor.php'),
        ], 'lorapok-config');
        
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/lorapok/execution-monitor'),
        ], 'lorapok-views');
        // Publish public assets (JS listener, CSS, icons)
        $this->publishes([
            __DIR__.'/resources/assets' => public_path('vendor/lorapok'),
        ], 'lorapok-assets');
        
        // Register middleware
        if ($this->shouldEnable()) {
            $this->registerMiddleware();
        }
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MonitorStatusCommand::class,
                Console\MonitorEnableCommand::class,
                Console\MonitorDisableCommand::class,
                Console\MonitorInstallCommand::class,
            ]);
        }
        // Register custom notification channel for Discord webhooks
        Notification::extend('discord', function ($app) {
            return new \Lorapok\ExecutionMonitor\Notifications\Channels\DiscordWebhookChannel();
        });
    }

    protected function shouldEnable(): bool
    {
        if (config('execution-monitor.auto_detect', true)) {
            return $this->autoDetectEnvironment();
        }
        return config('execution-monitor.enabled', false);
    }

    protected function autoDetectEnvironment(): bool
    {
        $currentEnv = App::environment();
        $allowedEnvs = config('execution-monitor.allowed_environments', ['local','development','dev','testing','staging']);
        return in_array($currentEnv, $allowedEnvs);
    }

    protected function isFeatureEnabled(string $feature): bool
    {
        return config("execution-monitor.features.{$feature}", false);
    }

    protected function registerMiddleware()
    {
        $kernel = $this->app[\Illuminate\Contracts\Http\Kernel::class];
        if ($this->isFeatureEnabled('routes')) {
            $kernel->pushMiddleware(Middleware\TrackExecutionTime::class);
        }
        if ($this->isFeatureEnabled('widget')) {
            $kernel->pushMiddleware(Middleware\InjectMonitorWidget::class);
        }
    }

    protected function trackDatabaseQueriesEarly()
    {
        // Use booted callback to ensure DB is ready but listener is registered early
        $this->app->booted(function () {
            DB::listen(function ($query) {
                if ($this->app->bound('execution-monitor')) {
                    $monitor = $this->app->make('execution-monitor');
                    if ($monitor->isEnabled()) {
                        $monitor->logQuery($query->sql, $query->time);
                    }
                }
            });
        });
    }
}
