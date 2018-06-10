<?php


namespace App\Services\RabbitMQ\Core;


class RabbitMQOptions {

    const DEFAULT_EXCHANGE = [
        'passive' => false,
        'durable' => false,
        'auto_delete' => true,
        'internal' => false,
        'nowait' => false,
        'arguments' => [],
        'ticket' => null //int|null
    ];

    const DEFAULT_QUEUE = [
        'queue' => '',
        'passive' => false,
        'durable' => false,
        'exclusive' => false,
        'auto_delete' => true,
        'nowait' => false,
        'arguments' => [],
        'ticket' => null //int|null
    ];

    const DEFAULT_BIND = [
        'routing_key' => '',
        'nowait' => false,
        'arguments' => [],
        'ticket' => null, //int|null
    ];

    const DEFAULT_PUBLISH = [
        'exchange' => '',
        'routing_key' => '',
        'mandatory' => false,
        'immediate' => false,
        'ticket' => null, // int|null
    ];

    const DEFAULT_CONSUME = [
        'queue' => '',
        'consumer_tag' => '',
        'no_local' => false,
        'no_ack' => false,
        'exclusive' => false,
        'nowait' => false,
        'ticket' => null, //int|null
        'arguments' => []
    ];

}