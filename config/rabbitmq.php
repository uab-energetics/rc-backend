<?php


use App\Services\RabbitMQ\Handlers\ExternalPublicationsAdded;
use App\Services\RabbitMQ\Handlers\ExternalPublicationsRemoved;
use App\Services\RabbitMQ\Handlers\ExternalUserCreated;

return [

    'connection' => [
        'host' => env(      'RABBITMQ_HOST', 'localhost'),
        'port' => env(      'RABBITMQ_PORT', 5672),
        'user' => env(      'RABBITMQ_USER', 'guest'),
        'password' => env(  'RABBITMQ_PASSWORD', 'guest'),

        'retries' => 24, // number of retries when connecting
        'wait_time' => 5, // time to wait between tries
    ],

    // see \App\Services\RabbitMQ\Core\RabbitMQOptions for options and defaults
    'default_overrides' => [
        'exchange_declare' => [
            'durable' => true,
            'auto_delete' => false,
        ],
        'queue_declare' => [
            'durable' => true,
            'auto_delete' => false,
        ],
        'queue_bind' => [],
        'basic_consume' => [],
        'basic_publish' => [],
    ],

    'exchanges' => [
        'resources.created' => [ 'type' => 'fanout' ],
        'users.created' => [ 'type' => 'fanout' ],
        'services.events' => [
            'type' => 'topic',
            'durable' => false,
        ],
    ],

    'queues' => [
        'process-new-user' => [],
        'process-new-publications' => [],
        'process-removed-publications' => [],
    ],

    'bindings' => [
        [
            'exchange' => 'users.created',
            'queue' => 'process-new-user',
        ],
        [
            'exchange' => 'services.events',
            'routing_key' => 'pub-repos.*.pubs-added',
            'queue' => 'process-new-publications',
        ],
        [
            'exchange' => 'services.events',
            'routing_key' => 'pub-repos.*.pubs-removed',
            'queue' => 'process-removed-publications',
        ]
    ],

    'handlers' => [
        [
            'queue' => 'process-new-user',
            'handler' => ExternalUserCreated::class
        ],
        [
            'queue' => 'process-new-publications',
            'handler' => ExternalPublicationsAdded::class
        ],
        [
            'queue' => 'process-removed-publications',
            'handler' => ExternalPublicationsRemoved::class
        ],
    ]

];
