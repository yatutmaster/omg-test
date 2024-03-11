<?php


return [
    'sber' => [
        'cacheKey'   => env('SBER_CACHE_KEY'),
        'host'       => env('SBER_HOST'),
        'maxRetries' => env('SBER_MAX_RETRIES', 3),
    ],
];