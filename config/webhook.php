<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Discord Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for Discord webhook logging.
    |
    */

    'discord' => [
        'enabled' => env('DISCORD_WEBHOOK_ENABLED', true),
        'default_webhook_url' => env('DISCORD_WEBHOOK_URL', null),
        'timeout' => env('DISCORD_WEBHOOK_TIMEOUT', 10),
        'retry_attempts' => env('DISCORD_WEBHOOK_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('DISCORD_WEBHOOK_RETRY_DELAY', 1000), // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which actions should be logged and their priorities.
    |
    */

    'logging' => [
        'enabled_actions' => [
            // Record actions
            'ban_create', 'ban_update', 'ban_delete',
            'warn_create', 'warn_update', 'warn_delete',
            'kick_create', 'kick_update', 'kick_delete',
            'note_create', 'note_update', 'note_delete',
            'commend_create', 'commend_update', 'commend_delete',
            'trustscore_create', 'trustscore_update', 'trustscore_delete',
            
            // Staff actions
            'staff_create', 'staff_update', 'staff_delete',
            'token_create', 'token_update', 'token_deactivate',
            
            // Dashboard actions
            'dashboard_create', 'dashboard_delete', 'dashboard_switch',
            'widget_add', 'widget_remove',
            
            // System actions
            'settings_update', 'player_join', 'player_leave',
        ],

        'priority_actions' => [
            'high' => ['ban_create', 'staff_create', 'staff_delete'],
            'medium' => ['warn_create', 'kick_create', 'dashboard_create'],
            'low' => ['note_create', 'commend_create', 'widget_add'],
        ],

        'excluded_routes' => [
            '/api/logs',
            '/api/health',
            '/api/metrics',
            '/_debugbar',
            '/telescope',
            '/horizon',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Embed Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how Discord embeds should look.
    |
    */

    'embed' => [
        'colors' => [
            'success' => 0x00ff00, // Green
            'warning' => 0xffa500, // Orange
            'error' => 0xff0000,   // Red
            'info' => 0x00bfff,    // Deep sky blue
            'ban' => 0xff0000,     // Red
            'warn' => 0xffa500,    // Orange
            'kick' => 0xff8c00,    // Dark orange
            'note' => 0x4169e1,    // Royal blue
            'commend' => 0xffd700, // Gold
            'trustscore' => 0x32cd32, // Lime green
            'staff' => 0x9370db,   // Medium purple
            'token' => 0x20b2aa,   // Light sea green
            'dashboard' => 0x00bfff, // Deep sky blue
            'widget' => 0x00ced1,  // Dark turquoise
        ],

        'footer' => [
            'text' => 'Badger Staff Panel',
            'icon_url' => null,
        ],

        'timestamp_format' => 'Y-m-d H:i:s',
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for webhook requests to prevent Discord API abuse.
    |
    */

    'rate_limiting' => [
        'enabled' => true,
        'max_requests_per_minute' => 30,
        'max_requests_per_hour' => 1000,
        'cooldown_period' => 60, // seconds
    ],
];
