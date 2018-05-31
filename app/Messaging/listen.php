<?php

use App\Messaging\RabbitConsumer;
use App\Messaging\RabbitMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = null;
$retries = config('rabbitmq.connection.retries');
$sleepTime = config('rabbitmq.connection.wait_time');

print("Trying to connect to RabbitMQ..." . PHP_EOL);
while ($connection === null && $retries > 0) {
    try {
        $connection = new AMQPStreamConnection(
            config('rabbitmq.connection.host'),
            config('rabbitmq.connection.port'),
            config('rabbitmq.connection.user'),
            config('rabbitmq.connection.password')
        );
    } catch (Exception $e) {
        print("Could not connect to RabbitMQ. $retries tries remaining..." . PHP_EOL);
        $retries--;
        sleep($sleepTime);
    }
}

if ($retries <= 0) {
    print("Failed to connect to RabbitMQ. Shutting Down." . PHP_EOL);
    exit(1);
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
