<?php
// Simple test script to dispatch a SlowRouteDetected notification to the configured Discord webhook.
// Run from project root with: php tools/trigger_discord_test.php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use the Notification facade
use Illuminate\Support\Facades\Notification;
use Lorapok\ExecutionMonitor\Notifications\SlowRouteDetected;

// Prepare sample route data (duration in seconds)
$routeData = [
    'path' => '/__lorapok_test/discord',
    'duration' => 1.75, // seconds
    'method' => 'GET',
];

$webhook = env('MONITOR_DISCORD_WEBHOOK');
if (empty($webhook)) {
    echo "MONITOR_DISCORD_WEBHOOK not configured in .env\n";
    exit(1);
}

try {
    Notification::route('discord_webhook', $webhook)
        ->notify(new SlowRouteDetected($routeData));
    echo "Discord notification dispatched (route notification).\n";
} catch (\Throwable $e) {
    echo "Dispatch failed: " . $e->getMessage() . "\n";
}

// (Optional) If you want the package helper to run, call it from a controller or make a public helper.
