<?php

return [
    'api_user_id' => env('SENDPULSE_API_USER_ID', '56f1945d61bf0d5849cad5f82d3893f5'),
    'api_secret' => env('SENDPULSE_API_SECRET', 'eb66206103f799d210814e8d7f208a9b'),

    /*
     *  Define where script will save access token
     * 
     *  Types: session, file
     */
    'token_storage' => env('TOKEN_STORAGE', 'file'),

    'storages' => [
        'file' => [
            'path' => '/app/'
        ]
    ]
];