<?php

namespace Tests\Feature;

use Tests\TestCase;
use Lorapok\ExecutionMonitor\Monitor;
use Lorapok\ExecutionMonitor\Analyzers\QueryPatternLibrary;
use Lorapok\ExecutionMonitor\Analyzers\EloquentSuggestionGenerator;
use Lorapok\ExecutionMonitor\Analyzers\CacheROIAnalyzer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Week2FeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_query_pattern_detection()
    {
        $library = new QueryPatternLibrary();
        
        $tips = $library->analyze("SELECT * FROM users");
        $this->assertEquals('select_all', $tips[0]['type']);

        $tips = $library->analyze("SELECT name FROM users WHERE name LIKE '%john%'");
        $this->assertEquals('leading_wildcard', $tips[0]['type']);
    }

    public function test_eloquent_suggestion_generation()
    {
        $generator = new EloquentSuggestionGenerator();
        $queries = [
            ['sql' => "SELECT * FROM `posts` WHERE `posts`.`user_id` = 1"],
            ['sql' => "SELECT * FROM `posts` WHERE `posts`.`user_id` = 2"],
            ['sql' => "SELECT * FROM `posts` WHERE `posts`.`user_id` = 3"],
            ['sql' => "SELECT * FROM `posts` WHERE `posts`.`user_id` = 4"],
        ];

        $suggestions = $generator->generate($queries);
        $this->assertNotEmpty($suggestions);
        $this->assertStringContainsString('with(\'user\')', $suggestions[0]['after']);
    }

    public function test_cache_roi_detection()
    {
        $analyzer = new CacheROIAnalyzer();
        $queries = [
            ['sql' => "SELECT * FROM large_table", 'time' => 500]
        ];

        $roi = $analyzer->analyze($queries);
        $this->assertNotEmpty($roi);
        $this->assertEquals(475, $roi[0]['potential_savings']); // 500 * 0.95
    }

    public function test_achievement_unlocking()
    {
        // Achievement tracking requires DB
        $monitor = new Monitor();
        
        // Simulate a request with many queries but no N+1
        for ($i = 0; $i < 10; $i++) {
            $monitor->logQuery("SELECT * FROM table_$i", 1);
        }

        $report = $monitor->getReport();
        (new \Lorapok\ExecutionMonitor\Services\AchievementTracker())->track($report);
        
        // Refresh report to get updated achievements from DB
        $report = $monitor->getReport();
        
        // Find Query Slayer achievement
        $slayer = collect($report['achievements'])->firstWhere('slug', 'query_slayer');
        $this->assertNotNull($slayer);
        $this->assertNotNull($slayer->unlocked_at);
    }
}
