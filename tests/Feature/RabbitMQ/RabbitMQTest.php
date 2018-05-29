<?php

namespace Tests\Feature\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Tests\TestCase;

class RabbitMQTest extends TestCase
{

    const CONFIG = [
        'rabbitmq.bindings' => [
            [
                'exchange' => 'test-ex',
                'queue' => 'test-qu',
                'event' => DummyEvent::class
            ]
        ]
    ];



    /** @var AMQPStreamConnection */
    public $connection;
    /** @var AMQPChannel */
    private $channel;

    public function setUp()
    {
        parent::setUp();

        $this->connection = new AMQPStreamConnection(
            config('rabbitmq.connection.host'),
            config('rabbitmq.connection.port'),
            config('rabbitmq.connection.user'),
            config('rabbitmq.connection.password')
        );
        $this->channel = $this->connection->channel();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->channel->close();
        $this->connection->close();
    }

    public function testCanBindQueue() {
        $exchange_name = 'test_exchange';
        $this->channel->exchange_declare($exchange_name, 'fanout', false, false, false);
        [$queue_name, ,] = $this->channel->queue_declare('test_queue', false, false, true, false);

        $this->channel->queue_bind($queue_name, $exchange_name);
        $this->assertTrue(true);
    }

}