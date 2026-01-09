<?php
namespace Lorapok\ExecutionMonitor\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\MailMessage;

class SlowRouteDetected extends Notification
{
    use Queueable;

    protected $routeData;

    public function __construct(array $routeData)
    {
        $this->routeData = $routeData;
    }

    public function via($notifiable)
    {
        $channels = [];
        if (config('execution-monitor.notifications.slack.enabled')) $channels[] = 'slack';
        if (config('execution-monitor.notifications.mail.enabled')) $channels[] = 'mail';
        if (config('execution-monitor.notifications.database.enabled')) $channels[] = 'database';
        if (config('execution-monitor.notifications.discord.enabled')) $channels[] = 'discord';
        return $channels;
    }

    public function toDiscord($notifiable)
    {
        $route = $this->routeData['path'] ?? 'unknown';
        $time = round(($this->routeData['duration'] ?? 0) * 1000, 2);

        $embed = [
            'title' => '⚠️ Slow Route Detected',
            'description' => "Route `{$route}` exceeded configured threshold",
            'color' => 16711680, // red
            'fields' => [
                ['name' => 'Route', 'value' => $route, 'inline' => true],
                ['name' => 'Time', 'value' => $time . ' ms', 'inline' => true],
                ['name' => 'Method', 'value' => $this->routeData['method'] ?? 'GET', 'inline' => true],
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        return [
            'embeds' => [$embed],
        ];
    }

    public function toSlack($notifiable)
    {
        $route = $this->routeData['path'] ?? 'unknown';
        $time = round(($this->routeData['duration'] ?? 0) * 1000, 2);

        return (new SlackMessage)
            ->error()
            ->content('⚠️ Slow Route Detected: ' . $route)
            ->attachment(function ($attachment) use ($route, $time) {
                $attachment->fields([
                    'Route' => $route,
                    'Time' => $time . ' ms',
                    'Method' => $this->routeData['method'] ?? 'GET',
                ])->color('danger');
            });
    }

    public function toMail($notifiable)
    {
        $route = $this->routeData['path'] ?? 'unknown';
        $time = round(($this->routeData['duration'] ?? 0) * 1000, 2);

        return (new MailMessage)
            ->error()
            ->subject('⚠️ Slow Route Detected')
            ->line('Route: ' . $route)
            ->line('Execution Time: ' . $time . ' ms')
            ->line('Please investigate.');
    }

    public function toArray($notifiable)
    {
        return $this->routeData;
    }
}
