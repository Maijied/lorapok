<?php

namespace Tests\Unit;

use Tests\TestCase;
use Lorapok\ExecutionMonitor\Monitor;
use Lorapok\ExecutionMonitor\PrivacyMasker;
use Lorapok\ExecutionMonitor\FingerprintGenerator;
use Illuminate\Support\Facades\Config;

class Week1FeaturesTest extends TestCase
{
    public function test_timeline_segments()
    {
        $monitor = new Monitor();
        usleep(10000); // 10ms
        $monitor->recordTimelineSegment('routing');
        
        $report = $monitor->getReport();
        $timeline = $report['timeline'];

        $this->assertCount(2, $timeline); // boot, routing (response is added in setRequestData)
        $this->assertEquals('boot', $timeline[0]['name']);
        $this->assertEquals('routing', $timeline[1]['name']);
    }

    public function test_fingerprint_generation_with_advanced_metrics()
    {
        $monitor = new Monitor();
        $monitor->setRequestData([
            'method' => 'GET',
            'path' => 'checkout',
            'duration' => 2.15,
            'status' => 200
        ]);

        // 42 total queries
        for ($i = 0; $i < 42; $i++) {
            $monitor->logQuery('SELECT 1', 1);
        }
        
        // Add 3 slow queries (over 100ms threshold)
        $monitor->logQuery('SELECT * FROM large_table', 150);
        $monitor->logQuery('SELECT * FROM large_table', 200);
        $monitor->logQuery('SELECT * FROM large_table', 120);

        $report = $monitor->getReport();
        $fingerprint = $report['fingerprint'];

        // Format: GET:/checkout | 2150ms | q=45 | slowQ=3 | mem=... | n1=1
        $this->assertStringContainsString('GET:/checkout', $fingerprint);
        $this->assertStringContainsString('2150ms', $fingerprint);
        $this->assertStringContainsString('q=45', $fingerprint);
        $this->assertStringContainsString('slowQ=3', $fingerprint);
        $this->assertStringContainsString('n1=1', $fingerprint); // 45 > 15
    }

    public function test_privacy_masking_with_reveal()
    {
        Config::set('execution-monitor.privacy.allow_reveal', true);
        
        $monitor = new Monitor();
        $sql = "SELECT * FROM users WHERE email = 'john@example.com' AND password = 'secret123'";
        $monitor->logQuery($sql, 10);

        $report = $monitor->getReport();
        $query = $report['queries'][0];

        $this->assertTrue($query['is_masked']);
        $this->assertStringNotContainsString('john@example.com', $query['sql']);
        $this->assertStringNotContainsString('secret123', $query['sql']);
        
        $this->assertEquals($sql, $query['original_sql']);
    }

    public function test_privacy_masking_no_reveal_outside_local()
    {
        Config::set('execution-monitor.privacy.allow_reveal', true);
        $this->app->detectEnvironment(fn() => 'production');
        
        $monitor = new Monitor();
        $sql = "SELECT * FROM users WHERE email = 'john@example.com'";
        $monitor->logQuery($sql, 10);

        $report = $monitor->getReport();
        $query = $report['queries'][0];

        $this->assertNull($query['original_sql']);
    }
}
