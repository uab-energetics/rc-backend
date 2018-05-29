<?php

namespace Tests\Feature\RabbitMQ;

use App\Services\RabbitMQ\RabbitMQService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQTest extends TestCase {

    public function setUp() {
        parent::setUp();
        $this->connection = $this->makeRabbitMQConnection();
    }

    public function tearDown() {
        parent::tearDown();
        $this->connection->close();
    }

    public function testRabbitMQChannel() {
        $channel = $this->connection->channel();

        $this->assertTrue($this->connection && $channel);

        $channel->close();
    }

    public function testBindQueue() {
        $channel = $this->connection->channel();

        $exchange_name = 'test_exchange';
        $channel->exchange_declare($exchange_name, 'fanout', false, false, false);
        [$queue_name,,] = $channel->queue_declare('test_queue', false, false, true, false);

        $channel->queue_bind($queue_name, $exchange_name);
    }

    /** @var AMQPStreamConnection */
    public $connection;

    private function makeRabbitMQConnection() {
        return new AMQPStreamConnection(
            config('rabbitmq.connection.host'),
            config('rabbitmq.connection.port'),
            config('rabbitmq.connection.user'),
            config('rabbitmq.connection.password')
        );
    }

}
