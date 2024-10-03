<?php

return [
    'client_id' => env('SHOPIFY_client_id'),
    'client_secret' => env('SHOPIFY_CLIENT_SECRET'),
    'scopes' => env('SHOPIFY_SCOPES', 'read_orders,write_orders'),
    'host' => env('SHOPIFY_HOST'),
    'auth_callback_url' => env('SHOPIFY_AUTH_CALLBACK_URL'),
    'test_shop_domain' => env('SHOPIFY_TEST_SHOP_DOMAIN'),
    'test_shop_name' => env('SHOPIFY_TEST_SHOP_NAME'),

    'access_token' => env('SHOPIFY_ACCESS_TOKEN'),
];