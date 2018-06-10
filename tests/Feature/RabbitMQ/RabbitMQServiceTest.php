<?php

namespace Tests\Feature\RabbitMQ;

use App\Services\RabbitMQ\Core\RabbitMQService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQServiceTest extends TestCase {

    public function testRabbitMQServiceResolved() {
        $service = app()->make(RabbitMQService::class);
        $this->assertTrue($service !== null);
    }

}
