<?php

namespace Tests\Feature;

use Tests\TestCase;
use Lorapok\ExecutionMonitor\Monitor;
use Lorapok\ExecutionMonitor\Services\PerformanceBudget;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class Week4FeaturesTest extends TestCase
{
    public function test_budget_check_violation()
    {
        Config::set('execution-monitor.budgets', [
            'checkout' => [
                'duration' => 1000,
                'queries' => 10
            ]
        ]);

        $service = new PerformanceBudget();
        
        // Violated duration
        $report = [
            'request' => ['path' => 'checkout', 'duration' => 1.5],
            'total_queries' => 5
        ];
        $violations = $service->check($report);
        $this->assertCount(1, $violations);
        $this->assertStringContainsString('Duration budget exceeded', $violations[0]);

        // Violated queries
        $report = [
            'request' => ['path' => 'checkout', 'duration' => 0.5],
            'total_queries' => 20
        ];
        $violations = $service->check($report);
        $this->assertCount(1, $violations);
        $this->assertStringContainsString('Query budget exceeded', $violations[0]);
    }

    public function test_before_after_comparison()
    {
        Cache::forever('lorapok_history', [
            ['route' => 'home', 'duration' => 0.1, 'timestamp' => '2026-01-01 10:00:00'],
            ['route' => 'home', 'duration' => 0.2, 'timestamp' => '2026-01-01 11:00:00'],
        ]);

        $monitor = new Monitor();
        $monitor->setRequestData([
            'path' => 'home',
            'duration' => 0.3, // Current is slower than avg (0.15)
            'status' => 200
        ]);

        $report = $monitor->getReport();
        $this->assertArrayHasKey('comparison', $report);
        $this->assertEquals(100, $report['comparison']['diff_percent']); // (0.3 - 0.15) / 0.15 = 100%
    }
}
