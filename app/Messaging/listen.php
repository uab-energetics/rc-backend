<?php

use App\Messaging\RabbitConsumer;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$consumer = new RabbitConsumer($channel);

function bindToEvent (RabbitConsumer $consumer) {
    return function ($exchange, $queue, $laravel_event) use ($consumer) {
        $consumer->registerListener($exchange, $queue, function($data) use ($laravel_event) {
            event($laravel_event, $data);
        });
    };
}

$bind = bindToEvent($consumer);

$bindings = require __DIR__ . '/bindings.php';

foreach ($bindings as $binding) {
    $bind(
        $binding['exchange'],
        $binding['queue'],
        $binding['event']
    );
}

$consumer->listen();
