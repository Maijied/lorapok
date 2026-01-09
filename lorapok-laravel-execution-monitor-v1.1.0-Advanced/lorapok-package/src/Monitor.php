<?php
namespace Lorapok\ExecutionMonitor;

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

    public function __construct()
    {
        $this->enabled = true;
        $this->timeline = new TimelineReporter();
        $this->masker = new PrivacyMasker();
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
        if ($totalQueries > 15) {
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
        $this->recordTimeline('routing');
    }
    
    public function endRoute($path, $duration)
    {
        if (isset($this->routes[$path])) {
            $this->routes[$path]["duration"] = $duration;
        }
    }
    
    public function getReport()
    {
        $reporter = new MonitorReporter();
        $totalQueries = count($this->queries);
        $slowQueryThreshold = config('execution-monitor.thresholds.query', 100);
        $slowQueries = 0;
        
        $queryPatternLibrary = new Analyzers\QueryPatternLibrary();
        $suggestionGenerator = new Analyzers\EloquentSuggestionGenerator();
        $cacheROIAnalyzer = new Analyzers\CacheROIAnalyzer();
        
        $queryTips = [];
        foreach ($this->queries as $q) {
            if (($q['time'] ?? 0) > $slowQueryThreshold) {
                $slowQueries++;
            }
            $queryTips = array_merge($queryTips, $queryPatternLibrary->analyze($q['sql']));
        }

        $n1Count = $totalQueries > 15 ? 1 : 0; // Simple heuristic for now

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
            "achievements" => (new Services\AchievementTracker())->getAchievements(),
            "views" => $this->views,
            "tenant_id" => $this->tenantId,
            "security_issues" => (new Analyzers\SecurityScanner())->scan(),
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

        $report['budget_violations'] = (new Services\PerformanceBudget())->check($report);
        $report['narrative'] = (new Reporters\NarrativeGenerator())->generate($report);
        $report['fingerprint'] = (new FingerprintGenerator())->generate($report);

        return $report;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function disable()
    {
        $this->enabled = false;
    }
}
