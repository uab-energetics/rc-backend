<?php

namespace App\Services\RabbitMQ\Core;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitPublisher {

    public function publishEvent($payload, $options = []) {
        $options = array_merge($this->publishOptions, $options);
        $msg = new AMQPMessage(
            $payload,
            [ 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $this->channel->basic_publish(
            $msg,
            $options['exchange'],
            $options['routing_key'],
            $options['mandatory'],
            $options['immediate'],
            $options['ticket']
        );
    }


    private $channel;
    private $publishOptions;

    public function __construct(AMQPChannel $channel, $defaultOverrides = []) {
        $this->channel = $channel;
        $this->publishOptions = array_merge(RabbitMQOptions::DEFAULT_PUBLISH, $defaultOverrides);
    }

}
