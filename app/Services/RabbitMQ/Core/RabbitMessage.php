<?php

namespace App\Services\RabbitMQ\Core;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMessage {

    /** @var AMQPChannel  */
    private $channel;
    /** @var AMQPMessage  */
    private $message;
    /** @var array */
    private $data;

    public function __construct(AMQPChannel $channel, AMQPMessage $message, $data) {
        $this->channel = $channel;
        $this->message = $message;
        $this->data = $data;
    }

    public function acknowledge($multiple = false) {
        $deliveryTag = $this->message->delivery_info['delivery_tag'];
        $this->channel->basic_ack($deliveryTag, $multiple);
    }

    public function payload() {
        return $this->data;
    }

    public function message() {
        return $this->message;
    }

    public function deliveryInfo() {
        return $this->message->delivery_info;
    }
}
