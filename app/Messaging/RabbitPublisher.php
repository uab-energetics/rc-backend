<?php

namespace App\Messaging;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitPublisher {

    private $channel;

    public function __construct(AMQPChannel $channel) {
        $this->channel = $channel;
    }

    public function publishEvent($exchange, $payload) {
        $msg = new AMQPMessage(
            json_encode($payload),
            [ 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $this->channel->basic_publish($msg, $exchange);
    }

}
