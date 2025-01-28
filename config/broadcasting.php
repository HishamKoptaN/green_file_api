<?php

return [
    'default' => env('BROADCAST_DRIVER', 'null'),
    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('f747ccc6b19399eef89c'),
            'secret' => env('3f917d9b66e17aca0f8d'),
            'app_id' => env('1869194'),
            'options' => [
                'cluster' => env('eu'),
                'host' => env('PUSHER_HOST') ?: 'api-' . env('PUSHER_APP_CLUSTER', 'mt1') . '.pusher.com',
                'port' => env('PUSHER_PORT', 443),
                'scheme' => env('PUSHER_SCHEME', 'https'),
                'encrypted' => true,
                'useTLS' => env('PUSHER_SCHEME', 'https') === 'https',
            ],
            'client_options' => [
                // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
