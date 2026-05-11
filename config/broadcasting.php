<?php

return [
    'options' => [
        'host' => env('PUSHER_HOST', '127.0.0.1'),
        'port' => env('PUSHER_PORT', 6001),
        'scheme' => env('PUSHER_SCHEME', 'http'),
        'useTLS' => false,
    ],
];