<?php

return [


    'key' => env('PUSHER_APP_KEY'),

    'secret' => env('PUSHER_APP_SECRET'),

    'app_id' => env('PUSHER_APP_ID'),


    'options' => [
        'cluster' => 'us2',
        'encrypted' => true
    ]


];
