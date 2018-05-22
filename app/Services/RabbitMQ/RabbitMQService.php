<?php


namespace App\Services\RabbitMQ;


use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService {

    public function publishMessage($exchange, $messageData) {
        $message = new AMQPMessage($messageData);
        $this->channel->basic_publish($message, $exchange);
    }

    /** @var AMQPChannel  */
    protected $channel;

    public function __construct(AMQPChannel $channel) {
        $this->channel = $channel;
    }
}