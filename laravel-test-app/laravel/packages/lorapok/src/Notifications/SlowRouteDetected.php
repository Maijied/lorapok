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
        return $channels;
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
