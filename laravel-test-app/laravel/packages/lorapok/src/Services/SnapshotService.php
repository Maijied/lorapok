<?php

namespace Lorapok\ExecutionMonitor\Services;

use Illuminate\Support\Facades\Cache;

class SnapshotService
{
    public function capture(array $report)
    {
        $snapshots = Cache::get('lorapok_snapshots', []);
        
        $snapshot = [
            'id' => uniqid('snap_'),
            'timestamp' => now()->toDateTimeString(),
            'method' => $report['request']['method'] ?? 'GET',
            'url' => url($report['request']['path'] ?? '/'),
            'headers' => request()->headers->all(),
            'input' => request()->all(),
            'duration' => $report['request']['duration'] ?? 0,
            'report' => $report
        ];

        array_unshift($snapshots, $snapshot);
        $snapshots = array_slice($snapshots, 0, 20); // Keep last 20 slow ones

        Cache::forever('lorapok_snapshots', $snapshots);
        return $snapshot['id'];
    }

    public function generateCurl(array $snapshot): string
    {
        $curl = "curl -X {$snapshot['method']} '{$snapshot['url']}'";
        
        foreach ($snapshot['headers'] as $name => $values) {
            foreach ($values as $value) {
                if (in_array(strtolower($name), ['host', 'content-length'])) continue;
                $curl .= " -H '{$name}: {$value}'";
            }
        }

        if (!empty($snapshot['input'])) {
            $data = json_encode($snapshot['input']);
            $curl .= " -d '{$data}'";
        }

        return $curl;
    }
}
