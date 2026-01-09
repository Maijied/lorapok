<?php

namespace Lorapok\ExecutionMonitor\Console;

use Illuminate\Console\Command;
use Lorapok\ExecutionMonitor\Analyzers\SecurityScanner;
use Lorapok\ExecutionMonitor\MonitorReporter;

class MonitorAuditCommand extends Command
{
    protected $signature = 'monitor:audit';
    protected $description = 'Run a full performance and security audit';

    public function handle()
    {
        $this->info('🐛 Starting Lorapok Performance Audit...');
        
        // 1. Security Check
        $this->newLine();
        $this->info('🔒 Checking Security...');
        $scanner = new SecurityScanner();
        $issues = $scanner->scan();
        
        if (empty($issues)) {
            $this->info('✅ No critical security issues found.');
        } else {
            foreach ($issues as $issue) {
                $this->error("[$issue[severity]] $issue[message]");
            }
        }

        // 2. Historical Performance
        $this->newLine();
        $this->info('📈 Analyzing Historical Performance...');
        $reporter = new MonitorReporter();
        $stats = $reporter->getStats();
        
        if ($stats['count'] > 0) {
            $this->line("Average Request Time: <fg=purple>{$stats['avg_duration']}ms</>");
            $this->line("Average Queries:      <fg=purple>{$stats['avg_queries']}</>");
            $this->line("Total Requests Tracked: {$stats['count']}");
        } else {
            $this->warn('No performance data available yet.');
        }

        // 3. Slowest Endpoints
        $this->newLine();
        $this->info('🐌 Slowest Endpoints (Top 5):');
        $slowest = $reporter->getSlowestRoutes(5);
        if (!empty($slowest)) {
            $this->table(
                ['Method', 'Route', 'Duration (ms)'],
                array_map(fn($s) => [$s['method'], $s['route'], round($s['duration'] * 1000)], $slowest)
            );
        }

        $this->newLine();
        $this->info('🏁 Audit Complete! Use Lorapok widget to optimize further.');
    }
}
