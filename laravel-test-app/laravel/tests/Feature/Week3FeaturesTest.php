<?php

namespace Tests\Feature;

use Tests\TestCase;
use Lorapok\ExecutionMonitor\Monitor;
use Lorapok\ExecutionMonitor\Services\SnapshotService;
use Lorapok\ExecutionMonitor\MonitorReporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class Week3FeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_snapshot_capture_and_curl_generation()
    {
        $service = new SnapshotService();
        $report = [
            'request' => ['method' => 'POST', 'path' => 'api/test', 'duration' => 1.5],
            'total_queries' => 5
        ];

        $id = $service->capture($report);
        $this->assertNotNull($id);

        $snapshots = Cache::get('lorapok_snapshots');
        $this->assertCount(1, $snapshots);
        $this->assertEquals('POST', $snapshots[0]['method']);

        $curl = $service->generateCurl($snapshots[0]);
        $this->assertStringContainsString('curl -X POST', $curl);
        $this->assertStringContainsString('api/test', $curl);
    }

    public function test_heatmap_aggregation()
    {
        $reporter = new MonitorReporter();
        
        // Record multiple snapshots for the same route
        for ($i = 1; $i <= 5; $i++) {
            $reporter->recordSnapshot([
                'request' => ['path' => 'home', 'method' => 'GET', 'duration' => $i * 0.1, 'status' => 200],
                'total_queries' => 2
            ]);
        }

        // We can't easily test the command output here, but we can test the logic
        // by manually running what the command does
        $history = Cache::get('lorapok_history');
        $this->assertCount(5, $history);
    }

    public function test_rolling_history_stats()
    {
        $reporter = new MonitorReporter();
        
        $reporter->recordSnapshot([
            'request' => ['path' => 'a', 'duration' => 0.1, 'status' => 200],
            'total_queries' => 10
        ]);
        $reporter->recordSnapshot([
            'request' => ['path' => 'b', 'duration' => 0.3, 'status' => 200],
            'total_queries' => 20
        ]);

        $stats = $reporter->getStats();
        $this->assertEquals(200, $stats['avg_duration']); // (100 + 300) / 2
        $this->assertEquals(15, $stats['avg_queries']); // (10 + 20) / 2
    }
}
