<?php

namespace App\Services\RabbitMQ\Core;

use PhpAmqpLib\Channel\AMQPChannel;

class RabbitConsumer {

    public function registerCallback($callback, $options = []) {
        $options['callback'] = $callback;
        return $this->consume($options);
    }

    public function consume($options = []) {
        $options = array_merge($this->consumeOptions, $options);
        return $this->channel->basic_consume(
            $options['queue'],
            $options['consumer_tag'],
            $options['no_local'],
            $options['no_ack'],
            $options['exclusive'],
            $options['nowait'],
            $options['callback'],
            $options['ticket'],
            $options['arguments']
        );
    }

    public function listen() {
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    /** @var AMQPChannel  */
    private $channel;
    private $consumeOptions;

    public function __construct(AMQPChannel $channel, $defaultOverrides = []) {
        $this->channel = $channel;
        $this->consumeOptions = array_merge(RabbitMQOptions::DEFAULT_CONSUME, $defaultOverrides);
    }



}
