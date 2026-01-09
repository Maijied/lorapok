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
        'notifications' => true,
        'broadcasting' => true,
    ],
    'widget' => [
        'position' => 'bottom-right',
        'excluded_routes' => [
            'login', 'register', 'password/*',
        ],
    ],
    'thresholds' => [
        'route' => env('MONITOR_ROUTE_THRESHOLD', 1000), // ms
        'query' => env('MONITOR_QUERY_THRESHOLD', 100), // ms
        'query_count' => env('MONITOR_QUERY_COUNT', 100), // count
        'memory' => env('MONITOR_MEMORY_THRESHOLD', 128), // MB
        'function' => 500,
    ],
    'log_routes' => false,
    'log_queries' => false,
    'log_functions' => false,
    'add_header' => true,
    'notifications' => [
        'enabled' => true,
        'slack' => [
            'enabled' => false,
            'webhook_url' => env('MONITOR_SLACK_WEBHOOK'),
            'channel' => '#monitoring',
        ],
        'discord' => [
            'enabled' => true,
            'webhook_url' => env('MONITOR_DISCORD_WEBHOOK'),
        ],
        'mail' => [
            'enabled' => true,
            'to' => env('MONITOR_MAIL_TO', 'admin@example.com'),
        ],
    ],
    'rate_limiting' => [
        'enabled' => env('MONITOR_RATE_LIMIT_ENABLED', true),
        'minutes' => env('MONITOR_RATE_LIMIT_MINUTES', 30), // Changed from max_per_hour to minutes
    ],
];
