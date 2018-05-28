<?php

namespace Tests\Feature;

use App\Services\RabbitMQ\RabbitMQService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQTest extends TestCase {

    public function testRabbitMQConnection() {
        $connection = new AMQPStreamConnection(
            config('rabbitmq.connection.host'),
            config('rabbitmq.connection.port'),
            config('rabbitmq.connection.user'),
            config('rabbitmq.connection.password')
        );
        $channel = $connection->channel();

        $this->assertTrue($connection && $channel);

        $channel->close();
        $connection->close();
    }

    public function testRabbitMQServiceResolved() {
        $service = app()->make(RabbitMQService::class);
        $this->assertTrue($service !== null);
    }

}