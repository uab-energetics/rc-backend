<?php

use App\Messaging\RabbitConsumer;
use App\Messaging\RabbitMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection;

try {
    $connection = new AMQPStreamConnection(
        config('rabbitmq.connection.host'),
        config('rabbitmq.connection.port'),
        config('rabbitmq.connection.user'),
        config('rabbitmq.connection.password')
    );
} catch (Exception $e) {
    print("Could not connect to RabbitMQ");
}

$channel = $connection->channel();
$bindings = config('rabbitmq.bindings');

$consumer = new RabbitConsumer($channel);

function bindToEvent (RabbitConsumer $consumer) {
    return function ($exchange, $queue, $rabbit_event) use ($consumer) {
        $consumer->registerListener($exchange, $queue, function(RabbitMessage $msg) use ($rabbit_event) {
            event(new $rabbit_event($msg));
            return true;
        });
    };
}

$bind = bindToEvent($consumer);

foreach ($bindings as $binding) {
    $bind(
        $binding['exchange'],
        $binding['queue'],
        $binding['event']
    );
}

print("Listening for RabbitMQ Messages" . PHP_EOL);
$consumer->listen();

print("Done listening. Closing connection". PHP_EOL);
$channel->close();
$connection->close();
