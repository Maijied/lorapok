<?php
namespace Tests\Unit;

use Tests\TestCase;
use Lorapok\ExecutionMonitor\Monitor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class MonitorRateLimitTest extends TestCase
{
    public function test_monitor_is_rate_limited_and_does_not_send_webhook()
    {
        // Arrange: enable notifications and discord webhook
        config()->set('execution-monitor.notifications.enabled', true);
        config()->set('execution-monitor.notifications.discord.enabled', true);
        config()->set('execution-monitor.notifications.discord.webhook_url', 'https://example.com/webhook');

        // Fake HTTP to capture any posts
        Http::fake();

        // Make RateLimiter return that we're over limit
        RateLimiter::shouldReceive('tooManyAttempts')->andReturn(true);

        $monitor = new Monitor();
        $monitor->startRoute('/slow');
        // end route with long duration (seconds)
        $monitor->endRoute('/slow', 2.5);

        // Act
        $monitor->checkThresholds();

        // Assert: nothing was posted because rate limiter prevented send
        Http::assertNothingSent();
    }
}
