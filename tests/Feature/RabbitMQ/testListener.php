<?php

use Tests\Feature\RabbitMQ\DummyEvent;


config([
    'rabbitmq.bindings' => [
        [
            'exchange' => 'test.created',
            'queue' => 'testingQueue',
            'event' => DummyEvent::class
        ]
    ]
]);

Artisan::call('rabbitmq:listen');