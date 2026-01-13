<?php
namespace Lorapok\ExecutionMonitor\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class DiscordWebhookChannel
{
    public function send($notifiable, Notification $notification)
    {
        $webhook = null;
        // Support Notification::route('discord_webhook', $url) or notifiable route
        if (method_exists($notifiable, 'routeNotificationFor')) {
            try { $webhook = $notifiable->routeNotificationFor('discord_webhook'); } catch (\Throwable $e) { $webhook = null; }
        }

        // Fallback: property
        if (empty($webhook) && isset($notifiable->discord_webhook)) {
            $webhook = $notifiable->discord_webhook;
        }

        // Or allow notification to supply webhook via toDiscordWebhook($notifiable)
        if (empty($webhook) && method_exists($notification, 'discordWebhookUrl')) {
            try { $webhook = $notification->discordWebhookUrl($notifiable); } catch (\Throwable $e) { $webhook = null; }
        }

        if (empty($webhook)) {
            return;
        }

        if (!method_exists($notification, 'toDiscord')) {
            return;
        }

        $payload = $notification->toDiscord($notifiable);
        if (is_array($payload)) {
            try {
                Http::withHeaders(['Accept' => 'application/json'])->post($webhook, $payload);
            } catch (\Throwable $e) {
                \Log::error('DiscordWebhookChannel: send failed', ['error' => $e->getMessage()]);
            }
        }
    }
}
