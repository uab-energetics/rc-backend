<?php

namespace Tests\Feature\RabbitMQ;

use App\Messaging\RabbitPublisher;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Tests\TestCase;
use Thread;

class RabbitMQTest extends TestCase {

    /** @var AMQPStreamConnection */
    public $connection;
    /** @var AMQPChannel */
    private $channel;

    public function setUp() {
        parent::setUp();

        config([
            'rabbitmq.bindings' => [
                [
                    'exchange' => 'test.created',
                    'queue' => 'testingQueue',
                    'event' => DummyEvent::class
                ]
            ]
        ]);

        $this->connection = new AMQPStreamConnection(
            config('rabbitmq.connection.host'),
            config('rabbitmq.connection.port'),
            config('rabbitmq.connection.user'),
            config('rabbitmq.connection.password')
        );
        $this->channel = $this->connection->channel();
    }

    public function tearDown() {
        parent::tearDown();
        $this->channel->close();
        $this->connection->close();
    }


    /**
     * NOTE - this function does nothing more than publish to an exchange, and must be run in collaboration with the 'testListener.php' script
     */
    public function testPublishConsume() {
        $publisher = new RabbitPublisher($this->channel);
        $publisher->publishEvent('test.created', [
            'user' => [
                'name' => 'Chris Rocco'
            ]
        ]);
        $this->assertTrue(true);
    }

}