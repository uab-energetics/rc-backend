<?php

namespace Tests\Feature\messaging;

use App\Messaging\RabbitPublisher;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Tests\TestCase;

class RabbitPublishConsumeTest extends TestCase {

    public function testPublishConsume() {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $publisher = new RabbitPublisher($channel);

        $publisher->publishEvent('users.created', [
            'user' => [
                'name' => 'Chris Rocco'
            ]
        ]);

        $this->assertTrue(true);
    }
}
