<?php

namespace Tests\Unit;

use Tests\TestCase;
use Lorapok\ExecutionMonitor\Monitor;
use Lorapok\ExecutionMonitor\MonitorReporter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdvancedMonitorTest extends TestCase
{
    public function test_request_response_profiling()
    {
        $monitor = new Monitor();
        
        $data = [
            'method' => 'POST',
            'path' => 'test-route',
            'status' => 201,
            'request_size' => 1024,
            'response_size' => 2048,
            'duration' => 0.5
        ];

        $monitor->setRequestData($data);
        $report = $monitor->getReport();

        $this->assertEquals('POST', $report['request']['method']);
        $this->assertEquals(201, $report['request']['status']);
        $this->assertEquals(1024, $report['request']['request_size']);
        $this->assertEquals(2048, $report['request']['response_size']);
    }

    public function test_middleware_timing()
    {
        $monitor = new Monitor();
        $monitor->logMiddleware('TestMiddleware', 0.123);

        $report = $monitor->getReport();

        $this->assertArrayHasKey('TestMiddleware', $report['middleware']);
        $this->assertEquals(0.123, $report['middleware']['TestMiddleware']['duration']);
    }

    public function test_recommendations_engine()
    {
        $monitor = new Monitor();
        
        // Seed slow query
        $monitor->logQuery('SELECT * FROM users', 150); // > 100ms threshold
        
        // Seed high query count
        for ($i = 0; $i < 20; $i++) {
            $monitor->logQuery('SELECT 1', 1);
        }

        $recommendations = $monitor->getRecommendations();

        $this->assertNotEmpty($recommendations);
        $this->assertTrue(collect($recommendations)->contains(fn($r) => str_contains($r, 'High number of queries')));
        $this->assertTrue(collect($recommendations)->contains(fn($r) => str_contains($r, 'Slow query detected')));
    }

    public function test_history_rolling_snapshots()
    {
        Cache::flush();
        $reporter = new MonitorReporter();
        
        for ($i = 1; $i <= 60; $i++) {
            $reporter->recordSnapshot([
                'request' => ['path' => "route-$i", 'method' => 'GET', 'duration' => $i * 0.1, 'status' => 200],
                'total_queries' => $i
            ]);
        }

        $history = $reporter->getHistory();
        
        $this->assertCount(50, $history);
        $this->assertEquals('route-60', $history[0]['route']);
        
        $slowest = $reporter->getSlowestRoutes(1);
        $this->assertEquals('route-60', $slowest[0]['route']);
    }
}
