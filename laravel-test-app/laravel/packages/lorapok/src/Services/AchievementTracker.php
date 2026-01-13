<?php

namespace Lorapok\ExecutionMonitor\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AchievementTracker
{
    protected $achievements = [
        'speed_soldier' => [
            'name' => '⚡ Speed Soldier',
            'description' => 'Reduced route time by 50% compared to previous runs.',
            'goal' => 1
        ],
        'query_slayer' => [
            'name' => '🗡️ Query Slayer',
            'description' => 'Eliminated all N+1 queries in a complex request.',
            'goal' => 1
        ],
        'memory_master' => [
            'name' => '🧠 Memory Master',
            'description' => '10 consecutive low-memory requests (< 20MB).',
            'goal' => 10
        ],
        'cache_champion' => [
            'name' => '💾 Cache Champion',
            'description' => 'Achieved a 95% cache hit rate.',
            'goal' => 1
        ],
        'error_evader' => [
            'name' => '🛡️ Error Evader',
            'description' => 'Successfully handled 50 requests without a single exception.',
            'goal' => 50
        ],
        'latency_legend' => [
            'name' => '🛸 Latency Legend',
            'description' => 'Maintained an average response time under 100ms for 20 requests.',
            'goal' => 20
        ],
        'payload_pilot' => [
            'name' => '📦 Payload Pilot',
            'description' => 'Optimized request payloads to stay under 50KB.',
            'goal' => 5
        ],
        'zero_gravity' => [
            'name' => '👨‍🚀 Zero Gravity',
            'description' => 'Execute 100 consecutive requests under 50ms.',
            'goal' => 100
        ],
        'database_zen' => [
            'name' => '🧘 Database Zen',
            'description' => 'Serve 50 consecutive requests with zero database queries.',
            'goal' => 50
        ],
        'clean_code_crusader' => [
            'name' => '⚔️ Clean Code Crusader',
            'description' => '1000 requests without a single exception or error.',
            'goal' => 1000
        ],
        'titanium_stability' => [
            'name' => '🏔️ Titanium Stability',
            'description' => '500 requests staying under 10MB memory usage.',
            'goal' => 500
        ]
    ];

    public function track(array $report)
    {
        if (!config('execution-monitor.achievements.enabled', true)) {
            return;
        }

        $this->checkMemoryMaster($report);
        $this->checkQuerySlayer($report);
        $this->checkErrorEvader($report);
        $this->checkLatencyLegend($report);
        
        // Harder Achievements
        $this->checkZeroGravity($report);
        $this->checkDatabaseZen($report);
        $this->checkCleanCodeCrusader($report);
        $this->checkTitaniumStability($report);
    }

    protected function checkZeroGravity(array $report)
    {
        $duration = ($report['request']['duration'] ?? 0) * 1000;
        $key = 'monitor_zero_gravity_consecutive';
        if ($duration < 50) {
            $this->increment($key, 'zero_gravity');
        } else {
            $this->reset($key, 'zero_gravity');
        }
    }

    protected function checkDatabaseZen(array $report)
    {
        $queries = $report['total_queries'] ?? 0;
        $key = 'monitor_db_zen_consecutive';
        if ($queries === 0) {
            $this->increment($key, 'database_zen');
        } else {
            $this->reset($key, 'database_zen');
        }
    }

    protected function checkCleanCodeCrusader(array $report)
    {
        $key = 'monitor_clean_code_consecutive';
        if (empty($report['last_exception'])) {
            $this->increment($key, 'clean_code_crusader');
        } else {
            $this->reset($key, 'clean_code_crusader');
        }
    }

    protected function checkTitaniumStability(array $report)
    {
        $memory = (float) $report['memory_peak'];
        $key = 'monitor_titanium_consecutive';
        if ($memory < 10) {
            $this->increment($key, 'titanium_stability');
        } else {
            $this->reset($key, 'titanium_stability');
        }
    }

    protected function increment(string $cacheKey, string $slug)
    {
        $count = Cache::get($cacheKey, 0) + 1;
        Cache::put($cacheKey, $count, 86400 * 7); // 1 week
        $this->updateProgress($slug, $count);
    }

    protected function reset(string $cacheKey, string $slug)
    {
        Cache::put($cacheKey, 0, 86400 * 7);
        $this->updateProgress($slug, 0);
    }

    protected function checkErrorEvader(array $report)
    {
        $consecutiveKey = 'monitor_error_evader_consecutive';
        if (empty($report['last_exception'])) {
            $count = Cache::get($consecutiveKey, 0) + 1;
            Cache::put($consecutiveKey, $count, 86400);
            $this->updateProgress('error_evader', $count);
        } else {
            Cache::put($consecutiveKey, 0, 86400);
            $this->updateProgress('error_evader', 0);
        }
    }

    protected function checkLatencyLegend(array $report)
    {
        $duration = ($report['request']['duration'] ?? 0) * 1000;
        $consecutiveKey = 'monitor_latency_legend_consecutive';
        
        if ($duration < 100) {
            $count = Cache::get($consecutiveKey, 0) + 1;
            Cache::put($consecutiveKey, $count, 86400);
            $this->updateProgress('latency_legend', $count);
        } else {
            Cache::put($consecutiveKey, 0, 86400);
            $this->updateProgress('latency_legend', 0);
        }
    }

    protected function checkMemoryMaster(array $report)
    {
        $memory = (float) $report['memory_peak'];
        $consecutiveKey = 'monitor_mem_master_consecutive';
        
        if ($memory < 20) {
            $count = Cache::get($consecutiveKey, 0) + 1;
            Cache::put($consecutiveKey, $count, 86400);
            
            $this->updateProgress('memory_master', $count);
        } else {
            Cache::put($consecutiveKey, 0, 86400);
            $this->updateProgress('memory_master', 0);
        }
    }

    protected function checkQuerySlayer(array $report)
    {
        if ($report['n1_queries_count'] === 0 && $report['total_queries'] > 5) {
            $this->unlock('query_slayer');
        }
    }

    protected function updateProgress(string $slug, int $progress)
    {
        $achievement = $this->achievements[$slug] ?? null;
        if (!$achievement) return;

        $existing = DB::table('monitor_achievements')->where('slug', $slug)->first();
        $newProgress = $existing ? max((int) $existing->progress, $progress) : $progress;

        DB::table('monitor_achievements')->updateOrInsert(
            ['slug' => $slug],
            [
                'name' => $achievement['name'],
                'description' => $achievement['description'],
                'goal' => $achievement['goal'],
                'progress' => $newProgress,
                'unlocked_at' => $newProgress >= $achievement['goal'] ? now() : ($existing->unlocked_at ?? null),
                'updated_at' => now(),
                'created_at' => $existing->created_at ?? now()
            ]
        );
    }

    protected function unlock(string $slug)
    {
        $this->updateProgress($slug, $this->achievements[$slug]['goal']);
    }

    public function getAchievements(): array
    {
        try {
            return DB::table('monitor_achievements')->get()->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
