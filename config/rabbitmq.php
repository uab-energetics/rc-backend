<?php

use App\Events\UserCreatedExternal;

return [

    'connection' => [
        'host' => env(      'RABBITMQ_HOST', 'localhost'),
        'port' => env(      'RABBITMQ_PORT', 5672),
        'user' => env(      'RABBITMQ_USER', 'guest'),
        'password' => env(  'RABBITMQ_PASSWORD', 'guest'),

        'retries' => 24, // number of retries when connecting
        'wait_time' => 5, // time to wait between tries
    ],

    // will be declared upon channel creation.
    // name => type
    'exchanges' => [
        'resources.created' => 'fanout',
        'users.created' => 'fanout',
    ],
    // declared as persistent by default
    'queues' => [
        'process-new-user',
    ],

    'bindings' => [
        [
            'exchange' => 'users.created',
            'queue' => 'process-new-user',
            'event' => UserCreatedExternal::class
        ]
    ]

];
