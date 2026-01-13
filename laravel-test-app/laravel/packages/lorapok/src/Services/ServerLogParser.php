<?php

namespace Lorapok\ExecutionMonitor\Services;

use Illuminate\Support\Facades\File;

class ServerLogParser
{
    public function getLatest(int $lines = 1000, ?string $date = null): array
    {
        $logDir = storage_path('logs');
        $files = glob($logDir . '/*.log');
        
        if (empty($files)) {
            return [];
        }

        $allLogs = [];
        $targetDate = $date ?: now()->format('Y-m-d');

        foreach ($files as $file) {
            $data = $this->readLastLines($file, $lines);
            $parsed = $this->parseLogs($data);
            
            // Filter by date (Default to today)
            $parsed = array_filter($parsed, function($log) use ($targetDate) {
                return str_starts_with($log['at'], $targetDate);
            });
            
            $allLogs = array_merge($allLogs, $parsed);
        }

        usort($allLogs, function($a, $b) {
            return strcmp($b['at'], $a['at']);
        });

        return array_slice($allLogs, 0, $lines);
    }

    public function clearLogs(): bool
    {
        $logDir = storage_path('logs');
        $files = glob($logDir . '/*.log');
        $success = true;
        foreach ($files as $file) {
            if (!unlink($file)) $success = false;
        }
        return $success;
    }

    protected function readLastLines(string $filename, int $lines): string
    {
        $handle = fopen($filename, "r");
        if (!$handle) return "";
        
        $linecount = 0;
        $pos = -2;
        $beginning = false;

        while ($linecount < $lines) {
            if (fseek($handle, $pos, SEEK_END) == -1) {
                $beginning = true;
                break;
            }
            $t = fgetc($handle);
            if ($t === "\n") {
                $linecount++;
            }
            $pos--;
        }

        if ($beginning) {
            rewind($handle);
        }

        $content = fread($handle, abs($pos));
        fclose($handle);

        return $content;
    }

    protected function parseLogs(string $content): array
    {
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*)/';
        $lines = explode("\n", trim($content));
        $logs = [];

        foreach ($lines as $line) {
            if (preg_match($pattern, $line, $matches)) {
                $logs[] = [
                    'at' => $matches[1],
                    'env' => $matches[2],
                    'level' => strtolower($matches[3]),
                    'msg' => $matches[4],
                    'full' => $line,
                    'type' => 'server'
                ];
            } elseif (!empty($logs) && trim($line) !== '') {
                // Multi-line support (Stack traces)
                $lastIndex = count($logs) - 1;
                $logs[$lastIndex]['msg'] .= "\n" . $line;
                $logs[$lastIndex]['full'] .= "\n" . $line;
            }
        }

        // Return most recent first
        return array_reverse($logs);
    }
}