<?php
namespace Tests\Unit;

use Illuminate\Support\Facades\Http;
use Lorapok\ExecutionMonitor\Notifications\Channels\DiscordWebhookChannel;
use Illuminate\Notifications\Notification;
use Tests\TestCase;

class DummyNotification extends Notification
{
    public function toDiscord($notifiable)
    {
        return ['content' => 'test message'];
    }
}

class DiscordWebhookChannelTest extends TestCase
{
    public function test_sends_post_request_to_webhook()
    {
        // Fake HTTP
        Http::fake();

        $channel = new DiscordWebhookChannel();

        // Notifiable as object with routeNotificationFor method
        $notifiable = new class {
            public function routeNotificationFor($channel)
            {
                return 'https://example.com/webhook';
            }
        };

        $notification = new DummyNotification();

        // Call send
        $channel->send($notifiable, $notification);

        // Assert a POST was made
        Http::assertSent(function ($request) {
            return $request->url() === 'https://example.com/webhook' && $request->method() === 'POST';
        });
    }

    public function test_embeds_payload_format()
    {
        Http::fake();
        $channel = new \Lorapok\ExecutionMonitor\Notifications\Channels\DiscordWebhookChannel();
        $notifiable = new class {
            public function routeNotificationFor($channel) { return 'https://example.com/webhook'; }
        };

        $notification = new class extends \Illuminate\Notifications\Notification {
            public function toDiscord($notifiable) {
                return ['embeds' => [[ 'title' => 'Test', 'fields' => [['name'=>'X','value'=>'Y']]]]];
            }
        };

        $channel->send($notifiable, $notification);

        Http::assertSent(function ($request) {
            $body = $request->data();
            return isset($body['embeds']) && is_array($body['embeds']) && $body['embeds'][0]['title'] === 'Test';
        });
    }

    public function test_handles_http_exceptions_gracefully()
    {
        // Make HTTP throw an exception from the client
        Http::fake(function () { throw new \Exception('network boom'); });

        $channel = new \Lorapok\ExecutionMonitor\Notifications\Channels\DiscordWebhookChannel();
        $notifiable = new class { public function routeNotificationFor($channel) { return 'https://example.com/webhook'; } };
        $notification = new class extends \Illuminate\Notifications\Notification { public function toDiscord($n){ return ['content'=>'x']; } };

        // Should not throw
        $channel->send($notifiable, $notification);
        $this->assertTrue(true);
    }
}
