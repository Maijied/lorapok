<?php

return [
    'auto_detect' => true,
    'enabled' => false,
    'allowed_environments' => [
        'local', 'development', 'dev', 'testing', 'staging',
    ],
    'features' => [
        'widget' => true,
        'routes' => true,
        'queries' => true,
        'functions' => true,
        'notifications' => false,
        'broadcasting' => false,
    ],
    'widget' => [
        'position' => 'bottom-right',
        'excluded_routes' => [
            'login', 'register', 'password/*',
        ],
    ],
    'thresholds' => [
        'route' => 1000,
        'query' => 100,
        'memory' => 128 * 1024 * 1024,
        'function' => 500,
    ],
    'log_routes' => false,
    'log_queries' => false,
    'log_functions' => false,
    'add_header' => true,
    'notifications' => [
        'enabled' => false,
        'slack' => [
            'enabled' => false,
            'webhook_url' => env('MONITOR_SLACK_WEBHOOK'),
            'channel' => '#monitoring',
        ],
        'discord' => [
            'enabled' => false,
            'webhook_url' => env('MONITOR_DISCORD_WEBHOOK'),
        ],
        'mail' => [
            'enabled' => false,
            'to' => env('MONITOR_MAIL_TO', 'admin@example.com'),
        ],
    ],
    'rate_limiting' => [
        'enabled' => true,
        'max_per_hour' => 10,
    ],
];
