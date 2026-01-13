<?php

namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MonitorMemoryCommand extends Command
{
    protected $signature = 'monitor:memory {--full : Show full memory file content}';
    protected $description = 'Summarize project memory and status for development context';

    public function handle()
    {
        $this->info('🧠 Loading Lorapok Project Memory...');
        $this->newLine();

        // 1. Locate MEMORY.md
        $paths = [
            base_path('lorapok-laravel-execution-monitor-v1.1.0-Advanced/lorapok-package/MEMORY.md'), // Current dev structure
            base_path('packages/lorapok/MEMORY.md'),
            base_path('vendor/lorapok/laravel-execution-monitor/MEMORY.md'),
            base_path('MEMORY.md'), // Project root fallback
        ];

        $memoryContent = null;
        $foundPath = null;

        foreach ($paths as $path) {
            if (File::exists($path)) {
                $memoryContent = File::get($path);
                $foundPath = $path;
                break;
            }
        }

        if ($memoryContent) {
            $this->comment("Source: $foundPath");
            $this->newLine();

            if ($this->option('full')) {
                $this->line($memoryContent);
            } else {
                // Parse and nicely format the markdown
                $lines = explode("\n", $memoryContent);
                $limit = 20;
                $printed = 0;
                
                foreach ($lines as $line) {
                    if (trim($line) === '') {
                        $this->line('');
                        continue;
                    }
                    
                    if (str_starts_with($line, '# ')) {
                        $this->info('  ' . strtoupper(substr($line, 2)));
                        $this->line('  ' . str_repeat('=', strlen($line)));
                    } elseif (str_starts_with($line, '## ')) {
                        $this->newLine();
                        $this->warn('  ' . substr($line, 3));
                    } elseif (str_starts_with($line, '### ')) {
                        $this->comment('    > ' . substr($line, 4));
                    } elseif (str_starts_with($line, '- ')) {
                        $this->line('      • ' . substr($line, 2));
                        $printed++;
                    } else {
                        $this->line('      ' . $line);
                    }

                    if ($printed >= $limit) {
                        $this->newLine();
                        $this->comment('      ... (use --full to see complete history)');
                        break;
                    }
                }
            }
        } else {
            $this->error('❌ MEMORY.md not found in expected locations.');
        }

        // 2. Git Status
        $this->newLine();
        $this->info('🐙 Recent Git Activity');
        $this->line(str_repeat('-', 40));
        
        try {
            $gitLog = shell_exec('git log -n 5 --pretty=format:"%h - %an, %ar : %s" 2>/dev/null');
            if ($gitLog) {
                foreach(explode("\n", $gitLog) as $logLine) {
                    $this->line("  " . $logLine);
                }
            } else {
                $this->warn('  Git log unavailable.');
            }
        } catch (\Throwable $e) {
            $this->warn('  Could not retrieve git log.');
        }

        $this->newLine();
        $this->info('✅ Context Ready.');
    }
}
