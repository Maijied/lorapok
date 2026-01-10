<?php
namespace Lorapok\ExecutionMonitor;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Config;
use Lorapok\ExecutionMonitor\Analyzers\CacheROIAnalyzer;
use Lorapok\ExecutionMonitor\Analyzers\EloquentSuggestionGenerator;
use Lorapok\ExecutionMonitor\Analyzers\QueryPatternLibrary;
use Lorapok\ExecutionMonitor\Analyzers\SecurityScanner;
use Lorapok\ExecutionMonitor\Reporters\NarrativeGenerator;
use Lorapok\ExecutionMonitor\Reporters\MonitorReporter;
use Lorapok\ExecutionMonitor\Services\AchievementTracker;
use Lorapok\ExecutionMonitor\Services\PerformanceBudget;
use Lorapok\ExecutionMonitor\Services\FingerprintGenerator;
use Lorapok\ExecutionMonitor\Reporters\TimelineReporter;
use Lorapok\ExecutionMonitor\Services\PrivacyMasker;
use Lorapok\ExecutionMonitor\Console\MonitorStatusCommand;
use Lorapok\ExecutionMonitor\Console\MonitorEnableCommand;
use Lorapok\ExecutionMonitor\Console\MonitorDisableCommand;
use Lorapok\ExecutionMonitor\Console\MonitorInstallCommand;
use Lorapok\ExecutionMonitor\Console\MonitorFindCommand;
use Lorapok\ExecutionMonitor\Console\MonitorHeatmapCommand;
use Lorapok\ExecutionMonitor\Console\MonitorReplayCommand;
use Lorapok\ExecutionMonitor\Console\MonitorExportCommand;
use Lorapok\ExecutionMonitor\Console\MonitorAuditCommand;
use Throwable;

class Monitor
{
    protected $timers = [];
    protected $queries = [];
    protected $routes = [];
    protected $requestData = [];
    protected $middlewareData = [];
    protected $views = [];
    protected $tenantId = null;
    protected $timeline;
    protected $masker;
    protected $enabled = true;
    protected $currentRoute = null;
    protected $viewPath = null;
    protected $controllerAction = null;
    public $lastException = null;

    public function setControllerAction(string $action)
    {
        $this->controllerAction = $action;
    }

    public function __construct()
    {
        $this->enabled = true;
        $this->timeline = new TimelineReporter();
        $this->masker = new PrivacyMasker();
    }

    public function recordViewPath(string $path)
    {
        $base = base_path();
        $base = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->viewPath = str_replace($base, '', $path);
    }

    public function recordTimeline(string $name)
    {
        $this->timeline->record($name);
    }

    public function recordTimelineSegment(string $name)
    {
        $this->recordTimeline($name);
    }

    public function setRequestData(array $data)
    {
        $this->requestData = $data;
        $this->recordTimeline('response');
    }

    public function logMiddleware($name, $duration)
    {
        $this->middlewareData[$name] = [
            'duration' => $duration
        ];
        $this->recordTimeline('middleware:' . class_basename($name));
    }

    public function logView($name, $duration)
    {
        $this->views[] = [
            'name' => $name,
            'duration' => $duration
        ];
    }

    public function setTenantId($id)
    {
        $this->tenantId = $id;
    }

    public function getRecommendations(): array
    {
        $recommendations = [];
        $totalQueries = count($this->queries);

        // Slow queries
        $queryCountThreshold = config('execution-monitor.thresholds.query_count', 50);
        if ($totalQueries > $queryCountThreshold) {
            $recommendations[] = "High number of queries detected ({$totalQueries}). Consider eager loading relationships to avoid N+1 issues.";
        }

        $slowQueryThreshold = config('execution-monitor.thresholds.query', 100);
        foreach ($this->queries as $q) {
            if (($q['time'] ?? 0) > $slowQueryThreshold) {
                $recommendations[] = "Slow query detected. Consider adding indexes or optimizing the SQL.";
                break;
            }
        }

        // Memory peak
        $memoryPeak = memory_get_peak_usage();
        if ($memoryPeak > 50 * 1024 * 1024) { // 50MB
            $recommendations[] = "High memory peak detected (" . round($memoryPeak / 1024 / 1024, 2) . " MB). Try profiling large collections or using cursors for big datasets.";
        }

        // Route duration
        $routeThreshold = config('execution-monitor.thresholds.route', 1000);
        if (isset($this->requestData['duration']) && $this->requestData['duration'] * 1000 > $routeThreshold) {
            $recommendations[] = "This route is taking longer than {$routeThreshold}ms. Consider caching the response or moving heavy work to a background queue.";
        }

        return $recommendations;
    }

    /**
     * Check configured thresholds and emit alerts if needed.
     */
    public function checkThresholds()
    {
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

        // Check query count
        $queryCount = count($this->queries);
        $queryCountThreshold = config('execution-monitor.thresholds.query_count', 100);
        if ($queryCount > $queryCountThreshold) {
            $this->sendAlert('high_query_count', [
                'count' => $queryCount,
                'route' => $this->currentRoute ?? 'N/A',
            ]);
        }

        // Check memory usage
        $memoryPeak = memory_get_peak_usage(true);
        $memoryThreshold = config('execution-monitor.thresholds.memory', 128) * 1024 * 1024; // Convert MB to bytes
        if ($memoryPeak > $memoryThreshold) {
            $this->sendAlert('high_memory', [
                'current' => memory_get_usage(true),
                'peak' => $memoryPeak,
            ]);
        }
    }

    /**
     * Get notification settings from JSON file (widget settings) with config fallback.
     */
    protected function getNotificationSettings()
    {
        $path = storage_path('app/lorapok/settings.json');
        if (File::exists($path)) {
            $settings = json_decode(File::get($path), true) ?? [];
            if (!empty($settings)) {
                // Ensure default rate limit
                if (!isset($settings['rate_limit_minutes'])) {
                    $settings['rate_limit_minutes'] = 30;
                }
                return $settings;
            }
        }
        
        // Fallback to config
        return [
            'discord_webhook' => config('execution-monitor.notifications.discord.webhook_url'),
            'discord_enabled' => config('execution-monitor.notifications.discord.enabled', false),
            'slack_webhook' => config('execution-monitor.notifications.slack.webhook_url'),
            'slack_enabled' => config('execution-monitor.notifications.slack.enabled', false),
            'mail_to' => config('execution-monitor.notifications.mail.to'),
            'mail_enabled' => config('execution-monitor.notifications.mail.enabled', false),
            'rate_limit_minutes' => 30,
        ];
    }

    /**
     * Get server information for alerts.
     */
    protected function getServerInfo()
    {
        return [
            'hostname' => gethostname(),
            'app_name' => config('app.name', 'Laravel'),
            'environment' => config('app.env', 'production'),
            'timestamp' => date('Y-m-d H:i:s'),
            'url' => request()->fullUrl() ?? 'N/A',
        ];
    }

    protected function sendAlert(string $type, array $data)
    {
        $settings = $this->getNotificationSettings();

        // Rate limiting
        $rateConfig = config('execution-monitor.rate_limiting', ['enabled' => true, 'max_per_hour' => 10]);
        $enabled = $rateConfig['enabled'] ?? true;
        $rateLimitMinutes = $settings['rate_limit_minutes'] ?? 30;

        $key = 'monitor_alert_' . $type;
        if ($enabled) {
            try {
                if (RateLimiter::tooManyAttempts($key, 1)) {
                    Log::debug('ExecutionMonitor: alert rate limited', ['type' => $type]);
                    return;
                }
                RateLimiter::hit($key, $rateLimitMinutes * 60);
            } catch (\Throwable $e) {
                // ignore rate limiter failures
            }
        }

        // Get server info
        $serverInfo = $this->getServerInfo();
        $data['server_info'] = $serverInfo;

        // Send directly to channels with professional formatting
        $this->sendProfessionalAlert($type, $data);

        // Broadcast event if enabled
        if (config('execution-monitor.notifications.broadcasting', false) || config('execution-monitor.notifications.broadcast', false)) {
            try {
                event(new Events\PerformanceAlertEvent($type, $data));
            } catch (\Throwable $e) {
                // ignore broadcast failures
            }
        }
    }

    protected function sendProfessionalAlert(string $type, array $data)
    {
        $settings = $this->getNotificationSettings();
        $serverInfo = $data['server_info'] ?? $this->getServerInfo();
        
        // Prepare alert metadata
        $alertMeta = $this->formatAlertMetadata($type, $data, $serverInfo);
        
        // Send to Discord
        if (!empty($settings['discord_enabled']) && !empty($settings['discord_webhook'])) {
            try {
                $this->sendDiscordAlert($settings['discord_webhook'], $alertMeta);
            } catch (\Throwable $e) {
                Log::error('ExecutionMonitor: Discord alert failed', ['error' => $e->getMessage()]);
            }
        }
        
        // Send to Slack
        if (!empty($settings['slack_enabled']) && !empty($settings['slack_webhook'])) {
            try {
                $this->sendSlackAlert($settings['slack_webhook'], $alertMeta);
            } catch (\Throwable $e) {
                Log::error('ExecutionMonitor: Slack alert failed', ['error' => $e->getMessage()]);
            }
        }
        
        // Send to Email
        if (!empty($settings['mail_enabled']) && !empty($settings['mail_to'])) {
            try {
                $this->sendEmailAlert($settings['mail_to'], $alertMeta, $settings);
            } catch (\Throwable $e) {
                Log::error('ExecutionMonitor: Email alert failed', ['error' => $e->getMessage()]);
            }
        }
    }

    protected function formatAlertMetadata(string $type, array $data, array $serverInfo)
    {
        $meta = [
            'type' => $type,
            'server' => $serverInfo,
            'icon' => '⚠️',
            'color' => 15158332, // Red
        ];

        switch ($type) {
            case 'slow_route':
                $duration = round(($data['duration'] ?? 0) * 1000, 2);
                $threshold = config('execution-monitor.thresholds.route', 1000);
                $exceeded = $threshold > 0 ? round((($duration - $threshold) / $threshold) * 100, 1) : 0;
                
                $meta['title'] = '⚠️ Slow Route Detected';
                $meta['description'] = 'Route exceeded performance threshold';
                $meta['fields'] = [
                    'Route' => ($data['method'] ?? 'GET') . ' ' . ($data['path'] ?? 'N/A'),
                    'Duration' => $duration . 'ms',
                    'Threshold' => $threshold . 'ms',
                    'Exceeded By' => $exceeded . '%',
                ];
                break;

            case 'slow_query':
                $time = round($data['time'] ?? 0, 2);
                $threshold = config('execution-monitor.thresholds.query', 100);
                $sql = $data['sql'] ?? 'N/A';
                if (strlen($sql) > 200) {
                    $sql = substr($sql, 0, 200) . '...';
                }
                
                $meta['title'] = '⚠️ Slow Query Detected';
                $meta['description'] = 'Database query exceeded performance threshold';
                $meta['fields'] = [
                    'Query' => $sql,
                    'Execution Time' => $time . 'ms',
                    'Threshold' => $threshold . 'ms',
                ];
                break;

            case 'high_query_count':
                $count = $data['count'] ?? 0;
                $threshold = config('execution-monitor.thresholds.query_count', 100);
                
                $meta['title'] = '⚠️ High Query Count';
                $meta['description'] = 'Too many database queries in single request';
                $meta['fields'] = [
                    'Query Count' => $count,
                    'Threshold' => $threshold,
                    'Route' => $data['route'] ?? 'N/A',
                ];
                break;

            case 'high_memory':
                $current = $data['current'] ?? 0;
                $peak = $data['peak'] ?? 0;
                $threshold = config('execution-monitor.thresholds.memory', 128);
                
                $meta['title'] = '⚠️ High Memory Usage';
                $meta['description'] = 'Memory usage exceeded threshold';
                $meta['fields'] = [
                    'Current Memory' => round($current / 1024 / 1024, 2) . ' MB',
                    'Peak Memory' => round($peak / 1024 / 1024, 2) . ' MB',
                    'Threshold' => $threshold . ' MB',
                ];
                break;

            case 'exception':
                $meta['icon'] = '🔥';
                $meta['color'] = 16711680; // Bright red
                $meta['title'] = '🔥 Exception Occurred';
                $meta['description'] = $data['message'] ?? 'An exception was thrown';
                $meta['fields'] = [
                    'File' => ($data['file'] ?? 'N/A') . ':' . ($data['line'] ?? '0'),
                    'Exception' => $data['class'] ?? 'Exception',
                ];
                if (!empty($data['trace'])) {
                    $trace = is_array($data['trace']) ? implode("\n", array_slice($data['trace'], 0, 3)) : substr($data['trace'], 0, 300);
                    $meta['fields']['Stack Trace'] = $trace;
                }
                break;
        }

        return $meta;
    }

    protected function sendDiscordAlert(string $webhook, array $meta)
    {
        $fields = [];
        foreach ($meta['fields'] as $name => $value) {
            $fields[] = [
                'name' => $name,
                'value' => (string)$value,
                'inline' => strlen((string)$value) < 50,
            ];
        }

        // Add server info
        $fields[] = [
            'name' => '🌐 Server',
            'value' => $meta['server']['hostname'] . ' (' . $meta['server']['environment'] . ')',
            'inline' => true,
        ];
        $fields[] = [
            'name' => '🕐 Time',
            'value' => $meta['server']['timestamp'],
            'inline' => true,
        ];

        $payload = [
            'username' => 'Lorapok Monitor',
            'embeds' => [[
                'title' => $meta['title'],
                'description' => $meta['description'],
                'color' => $meta['color'],
                'fields' => $fields,
                'footer' => [
                    'text' => 'Lorapok Execution Monitor | ' . $meta['server']['app_name']
                ],
            ]]
        ];

        Http::post($webhook, $payload);
    }

    protected function sendSlackAlert(string $webhook, array $meta)
    {
        // Support both Webhook URL and API Token
        if (str_starts_with($webhook, 'xox')) {
            try {
                $targetChannel = config('execution-monitor.notifications.slack.channel', '#general');
                
                $fields = [];
                foreach ($meta['fields'] as $name => $value) {
                    $fields[] = [
                        'type' => 'mrkdwn',
                        'text' => "*{$name}:*\n" . $value,
                    ];
                }

                $blocks = [
                    [
                        "type" => "header",
                        "text" => [
                            "type" => "plain_text",
                            "text" => "🐛 " . $meta['title'],
                            "emoji" => true
                        ]
                    ],
                    [
                        "type" => "section",
                        "text" => [
                            "type" => "mrkdwn",
                            "text" => $meta['description']
                        ]
                    ],
                    [
                        "type" => "section",
                        "fields" => $fields
                    ]
                ];

                Http::withToken($webhook)
                    ->post('https://slack.com/api/chat.postMessage', [
                        'channel' => $targetChannel,
                        'text' => $meta['title'],
                        'blocks' => $blocks
                    ]);
                return;
            } catch (\Throwable $e) {
                Log::error('ExecutionMonitor: slack api failed', ['error' => $e->getMessage()]);
            }
        }

        $fields = [];
        foreach ($meta['fields'] as $name => $value) {
            $fields[] = [
                'type' => 'mrkdwn',
                'text' => "*{$name}:*\n" . $value,
            ];
        }

        $blocks = [
            [
                'type' => 'header',
                'text' => [
                    'type' => 'plain_text',
                    'text' => $meta['title'],
                    'emoji' => true,
                ],
            ],
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => $meta['description'],
                ],
            ],
            [
                'type' => 'section',
                'fields' => $fields,
            ],
            [
                'type' => 'context',
                'elements' => [
                    [
                        'type' => 'mrkdwn',
                        'text' => '🌐 *Server:* ' . $meta['server']['hostname'] . ' (' . $meta['server']['environment'] . ') | 🕐 ' . $meta['server']['timestamp'],
                    ],
                ],
            ],
        ];

        $payload = [
            'text' => $meta['title'],
            'blocks' => $blocks,
        ];

        Http::post($webhook, $payload);
    }

    protected function sendEmailAlert(string $to, array $meta, array $settings)
    {
        // Override Mail Config if provided
        if (!empty($settings['mail_host'])) {
            config([
                'mail.mailers.smtp.host' => $settings['mail_host'],
                'mail.mailers.smtp.port' => $settings['mail_port'],
                'mail.mailers.smtp.username' => $settings['mail_username'],
                'mail.mailers.smtp.password' => $settings['mail_password'],
                'mail.mailers.smtp.encryption' => $settings['mail_encryption'],
                'mail.from.address' => $settings['mail_from_address'] ?? 'monitor@lorapok.com',
            ]);
        }

        $body = "Server: {$meta['server']['hostname']} ({$meta['server']['environment']})\n";
        $body .= "Application: {$meta['server']['app_name']}\n";
        $body .= "Time: {$meta['server']['timestamp']}\n\n";
        $body .= "ALERT: {$meta['title']}\n";
        $body .= str_repeat('━', 50) . "\n\n";
        $body .= "{$meta['description']}\n\n";

        foreach ($meta['fields'] as $name => $value) {
            $body .= "{$name}: {$value}\n";
        }

        $body .= "\n" . str_repeat('━', 50) . "\n";
        $body .= "Lorapok Execution Monitor\n";

        $subject = $meta['icon'] . ' [' . $meta['server']['hostname'] . '] ' . str_replace($meta['icon'] . ' ', '', $meta['title']);

        Mail::raw($body, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function reset()
    {
        $this->timers = [];
        $this->queries = [];
        $this->routes = [];
        $this->currentRoute = null;
        $this->timeline->reset();
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
        
        if (empty($this->queries)) {
            $this->recordTimeline('queries');
        }

        $this->queries[] = [
            "sql" => $sql,
            "time" => $time
        ];
    }
    
    public function startRoute($path)
    {
        $this->currentRoute = $path;
        $this->routes[$path] = ["start" => microtime(true)];
        $this->recordTimeline('routing');
    }
    
    public function endRoute($path, $duration)
    {
        if (isset($this->routes[$path])) {
            $this->routes[$path]["duration"] = $duration;
        }
    }
    
    public function getReport($store = true)
    {
        $reporter = new MonitorReporter();
        $totalQueries = count($this->queries);
        $slowQueryThreshold = config('execution-monitor.thresholds.query', 100);
        $slowQueries = 0;
        
        $queryPatternLibrary = new QueryPatternLibrary();
        $suggestionGenerator = new EloquentSuggestionGenerator();
        $cacheROIAnalyzer = new CacheROIAnalyzer();
        
        $queryTips = [];
        foreach ($this->queries as $q) {
            if (($q['time'] ?? 0) > $slowQueryThreshold) {
                $slowQueries++;
            }
            $queryTips = array_merge($queryTips, $queryPatternLibrary->analyze($q['sql']));
        }

        $queryCountThreshold = config('execution-monitor.thresholds.query_count', 50);
        $n1Count = $totalQueries > $queryCountThreshold ? 1 : 0; // Simple heuristic for now

        $maskedQueries = [];
        $allowReveal = config('execution-monitor.privacy.allow_reveal', false) && app()->environment('local');
        
        foreach ($this->queries as $q) {
            $maskedSql = $this->masker->mask($q['sql']);
            $maskedQueries[] = [
                'sql' => $maskedSql,
                'original_sql' => $allowReveal ? $q['sql'] : null,
                'is_masked' => $maskedSql !== $q['sql'],
                'time' => $q['time'],
                'tips' => $queryPatternLibrary->analyze($q['sql'])
            ];
        }

        $report = [
            "timers" => $this->timers,
            "queries" => $maskedQueries,
            "routes" => $this->routes,
            "request" => $this->requestData,
            "middleware" => $this->middlewareData,
            "total_queries" => $totalQueries,
            "slow_queries_count" => $slowQueries,
            "n1_queries_count" => $n1Count,
            "total_query_time" => array_sum(array_column($this->queries, "time")),
            "memory_peak" => round(memory_get_peak_usage() / 1024 / 1024, 2) . ' MB',
            "recommendations" => $this->getRecommendations(),
            "history" => $reporter->getHistory(),
            "stats" => $reporter->getStats(),
            "slowest_routes" => $reporter->getSlowestRoutes(5),
            "timeline" => $this->timeline->getTimeline(),
            "suggestions" => $suggestionGenerator->generate($this->queries),
            "cache_roi" => $cacheROIAnalyzer->analyze($this->queries),
            "achievements" => (new AchievementTracker())->getAchievements(),
            "views" => $this->views,
            "tenant_id" => $this->tenantId,
            "security_issues" => (new SecurityScanner())->scan(),
            "rate_limit_minutes" => $this->getNotificationSettings()['rate_limit_minutes'] ?? 30,
            "last_exception" => $this->lastException ? [
                'message' => $this->lastException->getMessage(),
                'file' => $this->lastException->getFile(),
                'line' => $this->lastException->getLine(),
                'trace' => array_slice($this->lastException->getTrace(), 0, 5) 
            ] : null,
            "view_path" => $this->viewPath,
            "controller_action" => $this->controllerAction,
        ];

        // Before/After Comparison
        $currentPath = $report['request']['path'] ?? '/';
        $historical = collect($report['history'])->where('route', $currentPath)->where('timestamp', '!=', $report['timestamp'] ?? '');
        if ($historical->isNotEmpty()) {
            $avgHist = $historical->avg('duration') * 1000;
            $currentDur = ($report['request']['duration'] ?? 0) * 1000;
            $report['comparison'] = [
                'avg_historical_ms' => round($avgHist, 2),
                'diff_ms' => round($currentDur - $avgHist, 2),
                'diff_percent' => $avgHist > 0 ? round((($currentDur - $avgHist) / $avgHist) * 100, 1) : 0,
            ];
        }

        $report['budget_violations'] = (new PerformanceBudget())->check($report);
        $report['narrative'] = (new NarrativeGenerator())->generate($report);
        $report['fingerprint'] = (new FingerprintGenerator())->generate($report);

        if ($store) {
            $reporter->recordSnapshot($report);
            (new AchievementTracker())->track($report);
        }

        return $report;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Send a test alert to verify configuration.
     */
    public function sendTestAlert(array $settings)
    {
        // Override Mail Config Dynamically if provided
        if (!empty($settings['mail_host'])) {
            config([
                'mail.mailers.smtp.host' => $settings['mail_host'],
                'mail.mailers.smtp.port' => $settings['mail_port'],
                'mail.mailers.smtp.username' => $settings['mail_username'],
                'mail.mailers.smtp.password' => $settings['mail_password'],
                'mail.mailers.smtp.encryption' => $settings['mail_encryption'],
                'mail.from.address' => $settings['mail_from_address'] ?? 'monitor@lorapok.com',
                'mail.default' => 'smtp',
            ]);
        }

        $routes = [];

        // Map settings to routes
        if (!empty($settings['slack_enabled'])) {
            $routes['slack'] = $settings['slack_webhook'] ?? null;
        }
        if (!empty($settings['discord_enabled'])) {
            $routes['discord'] = $settings['discord_webhook'] ?? null;
        }
        if (!empty($settings['mail_enabled'])) {
            $routes['mail'] = $settings['mail_to'] ?? null;
        }

        foreach ($routes as $channel => $destination) {
            if (empty($destination)) continue;

            try {
                // Slack Webhook Logic
                if ($channel === 'slack') {
                    try {
                        $payload = [
                            'text' => '✅ *Lorapok Connection Test*', 
                            'blocks' => [
                                [
                                    "type" => "header",
                                    "text" => [
                                        "type" => "plain_text",
                                        "text" => "✅ Connection Test Successful",
                                        "emoji" => true
                                    ]
                                ],
                                [
                                    "type" => "section",
                                    "text" => [
                                        "type" => "mrkdwn",
                                        "text" => "Your Lorapok configuration is working correctly.\n*Verified at:* " . date('Y-m-d H:i:s')
                                    ]
                                ]
                            ]
                        ];

                        Http::post($destination, $payload);
                    } catch (\Throwable $e) {
                        Log::error('ExecutionMonitor: slack webhook test failed', ['error' => $e->getMessage()]);
                        throw $e;
                    }
                    continue;
                }

                // Discord Logic Reuse
                if ($channel === 'discord') {
                     try {
                        $payload = [
                            'username' => 'Lorapok Monitor',
                            'embeds' => [
                                [
                                    'title' => '✅ Connection Test Successful',
                                    'description' => 'Your Lorapok configuration is working correctly.',
                                    'color' => 5763719, // Green
                                    'timestamp' => date('c'),
                                ]
                            ]
                        ];

                        Http::withHeaders([
                            'Accept' => 'application/json',
                        ])->post($destination, $payload);
                    } catch (\Throwable $e) {
                        throw $e;
                    }
                    continue;
                }
                
                // Mail Test
                if ($channel === 'mail') {
                    try {
                        Mail::raw("✅ Connection Test Successful!\n\nYour Lorapok configuration is working correctly.\n\nVerified at: " . date('Y-m-d H:i:s'), function ($message) use ($destination) {
                            $message->to($destination)
                                    ->subject('🐛 Lorapok Connection Test');
                        });
                    } catch (\Throwable $e) {
                        Log::error('ExecutionMonitor: mail test failed', ['error' => $e->getMessage()]);
                        throw $e;
                    }
                    continue;
                }

            } catch (\Throwable $e) {
                Log::error("ExecutionMonitor: Test alert failed for $channel", ['error' => $e->getMessage()]);
                throw $e; // Re-throw to let controller know
            }
        }
    }

    public function setException(\Throwable $e)
    {
        $this->lastException = $e;
    }
    
    public function disable()
    {
        $this->enabled = false;
    }
}