<?php

return [

    'connection' => [
        'host' => env(      'RABBITMQ_HOST', 'localhost'),
        'port' => env(      'RABBITMQ_PORT', 5672),
        'user' => env(      'RABBITMQ_USER', 'guest'),
        'password' => env(  'RABBITMQ_PASSWORD', 'guest'),
    ],

    // will be declared upon channel creation.
    // name => type
    'exchanges' => [
        'resource.created' => 'fanout',
    ],

    'bindings' => []

];
