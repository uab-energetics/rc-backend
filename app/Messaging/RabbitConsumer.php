<?php

namespace App\Messaging;

use PhpAmqpLib\Channel\AMQPChannel;

class RabbitConsumer {

    private $channel;

    public function __construct(AMQPChannel $channel) {
        $this->channel = $channel;
    }

    public function registerListener($exchange, $queue, $callback) {
        $this->channel->exchange_declare($exchange, 'fanout', false, false, false);
        list($queue_name, , ) = $this->channel->queue_declare($queue, false, false, true, false);
        $this->channel->queue_bind($queue_name, $exchange);
        $this->channel->basic_consume($queue_name, '', false, true, false, false, function($msg) use ($callback) {
            $callback(json_decode($msg->body, true));
            $this->channel->basic_ack($msg);
        });
    }

    public function listen() {
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

}
