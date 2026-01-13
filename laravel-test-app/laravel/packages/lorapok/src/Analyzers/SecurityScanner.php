<?php

namespace Lorapok\ExecutionMonitor\Analyzers;

class SecurityScanner
{
    public function scan(): array
    {
        $issues = [];

        // Check APP_DEBUG
        if (config('app.debug') && app()->environment('production')) {
            $issues[] = [
                'type' => 'security',
                'severity' => 'critical',
                'message' => 'APP_DEBUG is enabled in production! This can expose sensitive information.'
            ];
        }

        // Check for exposed secrets in config (simple checks)
        $sensitiveKeys = ['key', 'secret', 'password', 'token'];
        $configs = [
            'app.key' => config('app.key'),
            'database.connections' => config('database.connections'),
            'services' => config('services'),
        ];

        // This is a very basic check. Real scanner would be more thorough.
        if (empty($configs['app.key']) || $configs['app.key'] === 'base64:...') {
            $issues[] = [
                'type' => 'security',
                'severity' => 'high',
                'message' => 'Application key is missing or default.'
            ];
        }

        return $issues;
    }
}
