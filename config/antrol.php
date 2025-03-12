<?php

return [
    'env' => env('BPJS_ENV', 'development'),

    // Konfigurasi Development
    'development' => [
        'base_url'   => env('BPJS_DEV_BASE_URL', 'https://api.bpjs-dev.go.id'),
        'cons_id'    => env('BPJS_DEV_CONS_ID', '123456'),
        'secret_key' => env('BPJS_DEV_SECRET_KEY', 'abcdef123456'),
        'user_key'   => env('BPJS_DEV_USER_KEY_ANTROL', 'xyz123'),
    ],

    // Konfigurasi Production
    'production' => [
        'base_url'   => env('BPJS_PROD_BASE_URL', 'https://api.bpjs.go.id'),
        'cons_id'    => env('BPJS_PROD_CONS_ID', '654321'),
        'secret_key' => env('BPJS_PROD_SECRET_KEY', '123456abcdef'),
        'user_key'   => env('BPJS_DEV_USER_KEY_ANTROL', 'zyx321'),
    ],
];
