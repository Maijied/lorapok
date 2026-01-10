<?php
namespace Lorapok\ExecutionMonitor;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

use Lorapok\ExecutionMonitor\Console\MonitorStatusCommand;
use Lorapok\ExecutionMonitor\Console\MonitorEnableCommand;
use Lorapok\ExecutionMonitor\Console\MonitorDisableCommand;
use Lorapok\ExecutionMonitor\Console\MonitorInstallCommand;
use Lorapok\ExecutionMonitor\Console\MonitorFindCommand;
use Lorapok\ExecutionMonitor\Console\MonitorHeatmapCommand;
use Lorapok\ExecutionMonitor\Console\MonitorReplayCommand;
use Lorapok\ExecutionMonitor\Console\MonitorExportCommand;
use Lorapok\ExecutionMonitor\Console\MonitorAuditCommand;

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
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        
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
        
        // Merge persistent settings if they exist
        $settingsPath = storage_path('app/lorapok/settings.json');
        if (file_exists($settingsPath)) {
            $settings = json_decode(file_get_contents($settingsPath), true);
            if ($settings) {
                // Merge notifications config
                $notifications = [];
                if (isset($settings['discord_webhook'])) $notifications['discord']['webhook_url'] = $settings['discord_webhook'];
                if (isset($settings['discord_enabled'])) $notifications['discord']['enabled'] = $settings['discord_enabled'];
                if (isset($settings['slack_webhook'])) $notifications['slack']['webhook_url'] = $settings['slack_webhook'];
                if (isset($settings['slack_channel'])) $notifications['slack']['channel'] = $settings['slack_channel'];
                if (isset($settings['slack_enabled'])) $notifications['slack']['enabled'] = $settings['slack_enabled'];
                if (isset($settings['mail_to'])) $notifications['mail']['to'] = $settings['mail_to'];
                if (isset($settings['mail_enabled'])) $notifications['mail']['enabled'] = $settings['mail_enabled'];
                
                // Merge feature toggles
                $features = [];
                if (isset($settings['feature_widget'])) $features['widget'] = $settings['feature_widget'];
                if (isset($settings['feature_routes'])) $features['routes'] = $settings['feature_routes'];
                if (isset($settings['feature_queries'])) $features['queries'] = $settings['feature_queries'];
                if (isset($settings['feature_functions'])) $features['functions'] = $settings['feature_functions'];

                // Merge global enabled
                if (isset($settings['enabled'])) {
                    config(['execution-monitor.enabled' => $settings['enabled']]);
                    config(['execution-monitor.auto_detect' => false]);
                }

                config(['execution-monitor.notifications' => array_replace_recursive(
                    config('execution-monitor.notifications', []),
                    $notifications
                )]);
                
                if (!empty($features)) {
                    config(['execution-monitor.features' => array_replace_recursive(
                        config('execution-monitor.features', []),
                        $features
                    )]);
                }

                if (isset($settings['rate_limit_minutes'])) {
                    config(['execution-monitor.rate_limiting.max_per_hour' => 60 / $settings['rate_limit_minutes']]); // Rough conversion if needed
                }

                // Merge Thresholds
                if (isset($settings['route_threshold'])) config(['execution-monitor.thresholds.route' => $settings['route_threshold']]);
                if (isset($settings['query_threshold'])) config(['execution-monitor.thresholds.query' => $settings['query_threshold']]);
                if (isset($settings['query_count_threshold'])) config(['execution-monitor.thresholds.query_count' => $settings['query_count_threshold']]);
                if (isset($settings['memory_threshold'])) config(['execution-monitor.thresholds.memory' => $settings['memory_threshold']]);

                // Override Mail Server Config if provided
                if (!empty($settings['mail_host'])) {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $settings['mail_host'],
                        'mail.mailers.smtp.port' => $settings['mail_port'] ?? 587,
                        'mail.mailers.smtp.username' => $settings['mail_username'] ?? null,
                        'mail.mailers.smtp.password' => $settings['mail_password'] ?? null,
                        'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?? 'tls',
                        'mail.from.address' => $settings['mail_from_address'] ?? config('mail.from.address'),
                    ]);
                }
            }
        }

        // Register middleware
        if ($this->shouldEnable()) {
            $this->registerMiddleware();
        }
        
        $this->commands([
            Console\MonitorStatusCommand::class,
            Console\MonitorEnableCommand::class,
            Console\MonitorDisableCommand::class,
            Console\MonitorInstallCommand::class,
            Console\MonitorFindCommand::class,
            Console\MonitorHeatmapCommand::class,
            Console\MonitorReplayCommand::class,
            Console\MonitorExportCommand::class,
            Console\MonitorAuditCommand::class,
            Console\MonitorMemoryCommand::class,
        ]);
        
        // Register custom notification channel for Discord webhooks
        Notification::extend('discord', function ($app) {
            return new \Lorapok\ExecutionMonitor\Notifications\Channels\DiscordWebhookChannel();
        });

        // Deep Execution Tracking
        $this->app->booted(function () {
            // Track Cache Events
            \Illuminate\Support\Facades\Event::listen([
                \Illuminate\Cache\Events\CacheHit::class,
                \Illuminate\Cache\Events\CacheMiss::class,
            ], function ($event) {
                if (app()->bound('execution-monitor')) {
                    $hit = $event instanceof \Illuminate\Cache\Events\CacheHit;
                    app('execution-monitor')->logCache($event->key, $hit);
                }
            });

            // Track Queue Jobs
            \Illuminate\Support\Facades\Event::listen(\Illuminate\Queue\Events\JobProcessed::class, function ($event) {
                if (app()->bound('execution-monitor')) {
                    $duration = $event->job->getJobId() ? 0 : 0; // Duration logic can be complex, using simple log for now
                    app('execution-monitor')->logQueueJob($event->job->resolveName(), 'SUCCESS', 0);
                }
            });

            \Illuminate\Support\Facades\Event::listen(\Illuminate\Queue\Events\JobFailed::class, function ($event) {
                if (app()->bound('execution-monitor')) {
                    app('execution-monitor')->logQueueJob($event->job->resolveName(), 'FAILED', 0);
                }
            });

            // Track Blade Views
            \Illuminate\Support\Facades\Event::listen('composing:*', function ($event, $data = null) {
                if (app()->bound('execution-monitor')) {
                    $view = is_array($data) ? ($data[0] ?? null) : $event;
                    if ($view instanceof \Illuminate\View\View) {
                        app('execution-monitor')->recordViewPath($view->getPath());
                    }
                }
            });

            // Track Controller Actions
            \Illuminate\Support\Facades\Event::listen(\Illuminate\Routing\Events\RouteMatched::class, function ($event) {
                if (app()->bound('execution-monitor')) {
                    $monitor = app('execution-monitor');
                    $monitor->recordTimeline('controller');
                    
                    $action = $event->route->getActionName();
                    if ($action === 'Closure') {
                        $closure = $event->route->getAction('uses');
                        if ($closure instanceof \Closure) {
                            $rf = new \ReflectionFunction($closure);
                            $action = 'Closure at ' . basename($rf->getFileName()) . ':' . $rf->getStartLine();
                        }
                    }
                    $monitor->setControllerAction($action);
                }
            });
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
        $router = $this->app['router'];
        
        if ($this->isFeatureEnabled('routes')) {
            $router->prependMiddlewareToGroup('web', Middleware\TrackExecutionTime::class);
        }
        
        if ($this->isFeatureEnabled('widget')) {
            $router->pushMiddlewareToGroup('web', Middleware\InjectMonitorWidget::class);
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
