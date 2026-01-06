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
        return $channels;
    }

    public function toSlack($notifiable)
    {
        $sql = substr($this->queryData['sql'] ?? '', 0, 300);
        $time = $this->queryData['time'] ?? 0;

        return (new SlackMessage)
            ->warning()
            ->content('🐌 Slow Query Detected')
            ->attachment(function ($attachment) use ($sql, $time) {
                $attachment->fields([
                    'Query' => $sql,
                    'Time (ms)' => $time
                ])->color('warning');
            });
    }

    public function toArray($notifiable)
    {
        return $this->queryData;
    }
}
