<?php

namespace Tests\Feature\RabbitMQ;

use App\Messaging\RabbitMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DummyEvent {

    public function __construct(RabbitMessage $msg) {
        print("Received Message! \n");
        print(json_encode($msg->getPayload(), JSON_PRETTY_PRINT) . PHP_EOL);
        $msg->ack();
    }
}
