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

    public function testPublishConsume() {
        $publisher = new RabbitPublisher($this->channel);
        $publisher->publishEvent('test.created', [
            'user' => [
                'name' => 'Chris Rocco'
            ]
        ]);
        $this->assertTrue(true);
    }

    public function testBindQueue() {
        $exchange_name = 'test_exchange';
        $this->channel->exchange_declare($exchange_name, 'fanout', false, false, false);
        [$queue_name,,] = $this->channel->queue_declare('test_queue', false, false, true, false);

        $this->channel->queue_bind($queue_name, $exchange_name);

        $this->assertTrue(true);
    }

}