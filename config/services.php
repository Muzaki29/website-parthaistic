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

    'trello' => [
        'key' => env('TRELLO_KEY', env('TRELLO_API_KEY')),
        'token' => env('TRELLO_TOKEN', env('TRELLO_API_TOKEN')),
        'board_id' => env('TRELLO_BOARD_ID'),
        'lists' => [
            'todo' => env('TRELLO_LIST_TODO_ID'),
            'doing' => env('TRELLO_LIST_DOING_ID'),
            'done' => env('TRELLO_LIST_DONE_ID'),
        ],
    ],

];
