<?php

namespace Lorapok\ExecutionMonitor\Services;

class PrivacyMasker
{
    protected $patterns = [
        'email' => '/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}/i',
        'credit_card' => '/\b(?:\d[ -]*?){13,16}\b/',
        'password' => '/(password|passwd|pwd|secret)["\']?\s*[:=]\s*["\']?([^"\'\s,]+)["\']?/i',
        'token' => '/(token|auth|key|secret)["\']?\s*[:=]\s*["\']?([^"\'\s,]+)["\']?/i',
    ];

    public function mask(string $sql): string
    {
        if (!config('execution-monitor.privacy.auto_mask', true)) {
            return $sql;
        }

        foreach ($this->patterns as $pattern) {
            $sql = preg_replace_callback($pattern, function ($matches) {
                $match = $matches[0];
                if (str_contains($match, '@')) {
                    return $this->maskEmail($match);
                }
                return str_repeat('*', 8);
            }, $sql);
        }

        return $sql;
    }

    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];
        $maskedName = substr($name, 0, 1) . str_repeat('*', max(0, strlen($name) - 2)) . substr($name, -1);
        return $maskedName . '@' . $domain;
    }
}
