<?php

return [

    'vendor_id' => env('PADDLE_VENDOR_ID'),

    'vendor_auth_code' => env('PADDLE_VENDOR_AUTH_CODE'),

    'public_key' => env('PADDLE_PUBLIC_KEY'),
    'api_key' => env('PADDLE_API_KEY'),

    'client_token' => env('PADDLE_CLIENT_TOKEN'),

    'webhook_uri' => 'paddle/webhook',

    /**
     * https://developer.paddle.com/getting-started/sandbox
     */
    'sandbox_environment' => env('PADDLE_SANDBOX', false),

    'base_url' => 'https://sandbox-api.paddle.com',

];
