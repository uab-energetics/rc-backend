<?php

namespace Tests\Feature\messaging;

use App\Messaging\RabbitConsumer;
use App\Messaging\RabbitPublisher;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RabbitPublishConsumeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
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
