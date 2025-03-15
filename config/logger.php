<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'tracking' => [
        'default' => 'tracking'
    ],

    'user' => [
        'fields' => ['id', 'email', 'name'],
    ],

    'channels' => [
        'tracking' => [
            'driver' => 'daily',
            'path' => storage_path('logs/_models.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30,
            'active' => env('LOGGING_ROUTES_ACTIVE', true),
        ],
    ],

    'max_file_size' => 52428800, // Bytes

    'pattern'       => env('LOGVIEWER_PATTERN', '*.log'),
    'storage_path'  => env('LOGVIEWER_STORAGE_PATH', storage_path('logs')),
];
