<?php

return [
    /*
     * Default log channel
     *
     * This defines the default logging channel to be used by the application.
     * The value is retrieved from the LOG_CHANNEL environment variable.
     */
    'default' => env('LOG_CHANNEL', 'stack'),

    /*
     * Tracking logs configuration
     */
    'tracking' => [
        /*
         * Defines the default logging channel for tracking events.
         */
        'default' => 'tracking',
    ],

    'user' => [
        /*
         * Specifies which fields from the authenticated user should be included in the logs.
         */
        'fields' => ['id', 'email', 'name'],
    ],

    /*
     * Log channels configuration
     *
     * Defines different logging channels with their respective settings.
     */
    'channels' => [
        /*
         * Tracking log channels
         */
        'tracking' => [
            'driver' => 'daily',
            'path' => storage_path('logs/_tracking.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30,
            'active' => env('LOGGING_ROUTES_ACTIVE', true),
        ],
    ],
];
