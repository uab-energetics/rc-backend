<?php

namespace Tests\Feature\RabbitMQ;

use App\Messaging\RabbitPublisher;
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

        config(RabbitMQTest::CONFIG);

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


    /**
     * NOTE - this function does nothing more than publish to an exchange, and must be run in collaboration with the 'testListener.php' script
     */
    public function testPublishConsume()
    {
        $publisher = new RabbitPublisher($this->channel);
        $publisher->publishEvent(self::CONFIG['rabbitmq.bindings'][0]['exchange'], [
            'user' => [
                'name' => 'Chris Rocco'
            ]
        ]);
        $this->assertTrue(true);
    }

}