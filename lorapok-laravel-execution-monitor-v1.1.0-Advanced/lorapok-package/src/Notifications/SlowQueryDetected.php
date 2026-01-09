<?php
namespace Lorapok\ExecutionMonitor\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class SlowQueryDetected extends Notification
{
    use Queueable;

    protected $queryData;

    public function __construct(array $queryData)
    {
        $this->queryData = $queryData;
    }

    public function via($notifiable)
    {
        $channels = [];
        if (config('execution-monitor.notifications.slack.enabled')) $channels[] = 'slack';
        if (config('execution-monitor.notifications.database.enabled')) $channels[] = 'database';
        if (config('execution-monitor.notifications.discord.enabled')) $channels[] = 'discord';
        return $channels;
    }

    public function toSlack($notifiable)
    {
        $sql = substr($this->queryData['sql'] ?? '', 0, 300);
        $time = $this->queryData['time'] ?? 0;
        $url = config('app.url') . '/execution-monitor';

        return (new SlackMessage)
            ->warning()
            ->content('🐌 Slow Query Detected')
            ->attachment(function ($attachment) use ($sql, $time, $url) {
                $attachment->fields([
                    'Query' => $sql,
                    'Time (ms)' => $time
                ])->color('warning')
                ->action('View Report', $url);
            });
    }

    public function toArray($notifiable)
    {
        return $this->queryData;
    }

    public function toDiscord($notifiable)
    {
        $sql = substr($this->queryData['sql'] ?? '', 0, 1000);
        $time = $this->queryData['time'] ?? 0;
        $url = config('app.url') . '/execution-monitor';

        $embed = [
            'title' => '🐌 Slow Query Detected',
            'description' => "A slow database query was detected\n\n[🔍 View Report]({$url})",
            'color' => 16753920, // orange
            'fields' => [
                ['name' => 'Time (ms)', 'value' => (string) $time, 'inline' => true],
                ['name' => 'SQL', 'value' => $sql, 'inline' => false],
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        return ['embeds' => [$embed]];
    }
}
