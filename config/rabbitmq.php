<?php

use App\Events\External\PublicationsAddedExternal;
use App\Events\External\UserCreatedExternal;

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
        'pub-repos.pubs-added' => 'fanout',
        'pub-repos.pubs-removed' => 'fanout',
    ],
    // declared as persistent by default
    'queues' => [
        'process-new-user',
        'process-new-publications',
        'process-removed-publications',
    ],

    'bindings' => [
        [
            'exchange' => 'users.created',
            'queue' => 'process-new-user',
            'event' => UserCreatedExternal::class
        ],
        [
            'exchange' => 'pub-repos.pubs-added',
            'queue' => 'process-new-publications',
            'event' => PublicationsAddedExternal::class
        ],
//        [
//            'exchange' => 'pub-repos.pubs-removed',
//            'queue' => 'process-removed-publications',
////            'event' =>
//        ]
    ]

];
