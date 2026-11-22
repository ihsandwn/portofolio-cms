<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // AI Lab handoff. Each app has its own shared secret and public origin; the
    // audience key must match the app's slug so a session cannot cross apps.
    'ai_lab' => [
        'cache_store' => env('AI_LAB_CACHE_STORE'),
        'apps' => [
            'sentiment-analyzer' => [
                'url' => env('AI_LAB_SENTIMENT_URL'),
                'secret' => env('AI_LAB_SENTIMENT_SECRET'),
            ],
            'hr-screening' => [
                'url' => env('AI_LAB_HR_URL'),
                'secret' => env('AI_LAB_HR_SECRET'),
            ],
            'image-caption' => [
                'url' => env('AI_LAB_IMAGE_URL'),
                'secret' => env('AI_LAB_IMAGE_SECRET'),
            ],
            'pdf-rag-chatbot' => [
                'url' => env('AI_LAB_PDF_URL'),
                'secret' => env('AI_LAB_PDF_SECRET'),
            ],
        ],
    ],

];
