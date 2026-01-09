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
        ]
    ];

    public function track(array $report)
    {
        if (!config('execution-monitor.achievements.enabled', true)) {
            return;
        }

        $this->checkMemoryMaster($report);
        $this->checkQuerySlayer($report);
        // More checks can be added here
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
