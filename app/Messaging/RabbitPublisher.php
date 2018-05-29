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
        // TODO - validate the exchange
        $this->channel->exchange_declare($exchange, 'fanout', false, false, false);
        $this->channel->basic_publish(new AMQPMessage(json_encode($payload)), $exchange);
    }

}
