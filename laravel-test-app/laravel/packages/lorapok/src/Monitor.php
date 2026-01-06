<?php
namespace Lorapok\ExecutionMonitor;

class Monitor
{
    protected $timers = [];
    protected $queries = [];
    protected $routes = [];
    protected $enabled = true;
    protected $currentRoute = null;

    public function __construct()
    {
        $this->enabled = true;
    }

    /**
     * Check configured thresholds and emit alerts if needed.
     */
    public function checkThresholds()
    {
        if (!config('execution-monitor.notifications.enabled', false)) {
            return;
        }

        // Check routes
        foreach ($this->routes as $path => $data) {
            if (!isset($data['duration'])) continue;
            $durationMs = $data['duration'] * 1000;
            $threshold = config('execution-monitor.thresholds.route', 1000);
            if ($durationMs > $threshold) {
                $this->sendAlert('slow_route', [
                    'path' => $path,
                    'duration' => $data['duration'],
                    'method' => $data['method'] ?? 'GET'
                ]);
            }
        }

        // Check queries
        $queryThreshold = config('execution-monitor.thresholds.query', 100);
        foreach ($this->queries as $q) {
            $time = $q['time'] ?? ($q->time ?? 0);
            if ($time > $queryThreshold) {
                $this->sendAlert('slow_query', [
                    'sql' => $q['sql'] ?? ($q->sql ?? ''),
                    'time' => $time
                ]);
            }
        }
    }

    protected function sendAlert(string $type, array $data)
    {
        // Rate limiting
        $rateConfig = config('execution-monitor.rate_limiting', ['enabled' => true, 'max_per_hour' => 10]);
        $enabled = $rateConfig['enabled'] ?? true;
        $max = $rateConfig['max_per_hour'] ?? 10;

        $key = 'monitor_alert_' . $type;
        if ($enabled) {
            try {
                if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, $max)) {
                    \Log::debug('ExecutionMonitor: alert rate limited', ['type' => $type]);
                    return;
                }
                \Illuminate\Support\Facades\RateLimiter::hit($key, 3600);
            } catch (\Throwable $e) {
                // ignore rate limiter failures
            }
        }

        // Build notification object
        $notification = null;
        if ($type === 'slow_route') {
            $notification = new Notifications\SlowRouteDetected($data);
        } elseif ($type === 'slow_query') {
            $notification = new Notifications\SlowQueryDetected($data);
        }

        if ($notification) {
            $this->sendOnDemandNotification($notification);
        }

        // Broadcast event if enabled
        if (config('execution-monitor.notifications.broadcasting', false) || config('execution-monitor.notifications.broadcast', false)) {
            try {
                event(new Events\PerformanceAlertEvent($type, $data));
            } catch (\Throwable $e) {
                // ignore broadcast failures
            }
        }
    }

    protected function sendOnDemandNotification($notification)
    {
        $routes = [];

        // Slack
        if (config('execution-monitor.notifications.slack.enabled', false)) {
            $routes['slack'] = config('execution-monitor.notifications.slack.webhook_url');
        }

        // Discord
        if (config('execution-monitor.notifications.discord.enabled', false)) {
            $routes['discord'] = config('execution-monitor.notifications.discord.webhook_url');
        }

        // Mail
        if (config('execution-monitor.notifications.mail.enabled', false)) {
            $routes['mail'] = config('execution-monitor.notifications.mail.to');
        }

        foreach ($routes as $channel => $destination) {
            if (empty($destination)) continue;
            try {
                // Discord: send webhook POST directly to avoid requiring a custom notification channel
                if ($channel === 'discord') {
                    try {
                        $payload = [
                            'username' => config('app.name', 'ExecutionMonitor'),
                            'embeds' => [
                                [
                                    'title' => 'Execution Monitor Alert',
                                    'description' => json_encode($notification->toArray(null)),
                                    'color' => 15158332,
                                    'fields' => [],
                                ]
                            ]
                        ];

                        \Illuminate\Support\Facades\Http::withHeaders([
                            'Accept' => 'application/json',
                        ])->post($destination, $payload);
                    } catch (\Throwable $e) {
                        \Log::error('ExecutionMonitor: discord webhook failed', ['error' => $e->getMessage()]);
                    }
                    continue;
                }

                \Illuminate\Support\Facades\Notification::route($channel, $destination)->notify($notification);
            } catch (\Throwable $e) {
                \Log::error('ExecutionMonitor: notification failed', ['channel' => $channel, 'error' => $e->getMessage()]);
            }
        }
    }

    public function reset()
    {
        $this->timers = [];
        $this->queries = [];
        $this->routes = [];
        $this->currentRoute = null;
    }

    public function start($name)
    {
        if (!$this->enabled) return $this;
        
        $this->timers[$name] = [
            "start" => microtime(true),
            "memory_start" => memory_get_usage()
        ];
        
        return $this;
    }
    
    public function end($name)
    {
        if (!$this->enabled) return 0;
        
        if (!isset($this->timers[$name])) {
            throw new \Exception("Timer was not started");
        }
        
        $elapsed = microtime(true) - $this->timers[$name]["start"];
        $this->timers[$name]["elapsed"] = $elapsed;
        
        return $elapsed;
    }
    
    public function logQuery($sql, $time)
    {
        if (!$this->enabled) return;
        
        $this->queries[] = [
            "sql" => $sql,
            "time" => $time
        ];
    }
    
    public function startRoute($path)
    {
        $this->currentRoute = $path;
        $this->routes[$path] = ["start" => microtime(true)];
    }
    
    public function endRoute($path, $duration)
    {
        if (isset($this->routes[$path])) {
            $this->routes[$path]["duration"] = $duration;
        }
    }
    
    public function getReport()
    {
        return [
            "timers" => $this->timers,
            "queries" => $this->queries,
            "routes" => $this->routes,
            "total_queries" => count($this->queries),
            "total_query_time" => array_sum(array_column($this->queries, "time")),
        ];
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
