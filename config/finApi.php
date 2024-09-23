<?php

return [
    'default' => [
        'clientId' => env('FINAPI_DEFAULT_CLIENT_ID', 'default_client_id'),
        'clientSecret' => env('FINAPI_DEFAULT_CLIENT_SECRET', 'default_client_secret'),
        'dataDecryptionKey' => env('FINAPI_DEFAULT_DATA_DECRYPTION_KEY', 'default_data_decryption_key'),
    ],
    'admin' => [
        'clientId' => env('FINAPI_ADMIN_CLIENT_ID', 'admin_client_id'),
        'clientSecret' => env('FINAPI_ADMIN_CLIENT_SECRET', 'admin_client_secret'),
        'dataDecryptionKey' => env('FINAPI_ADMIN_DATA_DECRYPTION_KEY', 'admin_data_decryption_key'),
    ],
    'finApiServerUrl' => env('FINAPI_API_SERVER_URL', 'https://sandbox.finapi.io'),
    'grant_type' => [
        'client_credentials' => 'client_credentials',
        'password' => 'password',
        'refresh_token' => 'refresh_token',
    ],
];