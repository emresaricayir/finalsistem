<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business API Configuration
    |--------------------------------------------------------------------------
    |
    | Meta WhatsApp Business API ayarları
    | https://developers.facebook.com/docs/whatsapp/cloud-api
    |
    */

    'app_id' => env('WHATSAPP_APP_ID'),
    'app_secret' => env('WHATSAPP_APP_SECRET'),
    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    'webhook_verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => env('WHATSAPP_RATE_LIMIT', 80), // mesaj/saniye
    'daily_limit' => env('WHATSAPP_DAILY_LIMIT', 1000), // ücretsiz limit

    /*
    |--------------------------------------------------------------------------
    | API Version
    |--------------------------------------------------------------------------
    */
    'api_version' => env('WHATSAPP_API_VERSION', 'v18.0'),
];
