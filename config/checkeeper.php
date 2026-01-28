<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Checkeeper API Key
    |--------------------------------------------------------------------------
    |
    | Your Checkeeper API key for authentication. Get this from your
    | Checkeeper dashboard at https://app.checkeeper.com
    |
    */
    'api_key' => env('CHECKEEPER_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Checkeeper API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Checkeeper API. Defaults to production endpoint.
    | Override for testing environments if needed.
    |
    */
    'base_url' => env('CHECKEEPER_BASE_URL', 'https://api.checkeeper.com/v3'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeout
    |--------------------------------------------------------------------------
    |
    | Maximum time (in seconds) to wait for API responses before timing out.
    |
    */
    'timeout' => 30,

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Configure automatic retry behavior for failed HTTP requests.
    |
    */
    'retry' => [
        'times' => 3,
        'sleep' => 100, // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configure webhook handling for real-time status updates from Checkeeper.
    |
    */
    'webhooks' => [
        'enabled' => true,
        'secret' => env('CHECKEEPER_WEBHOOK_SECRET'),
        'route' => 'checkeeper/webhook',
        'middleware' => ['api'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    |
    | Configure queue settings for async operations like check creation.
    |
    */
    'queue' => [
        'enabled' => true,
        'connection' => env('CHECKEEPER_QUEUE_CONNECTION', 'default'),
        'queue' => env('CHECKEEPER_QUEUE_NAME', 'checkeeper'),
    ],
];
