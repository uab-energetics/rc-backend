<?php


namespace App\Services\RabbitMQ;


use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService {

    public function publishMessage($exchange, $messageData) {
        $message = new AMQPMessage(json_encode($messageData));
        $this->channel->basic_publish($message, $exchange);
    }

    /** @var AMQPChannel  */
    protected $channel;

    public function __construct(AMQPChannel $channel) {
        $this->channel = $channel;
    }

    static function projectCreated($project_id, $user_id) {
        return [
            RABBITMQ_RESOURCE_CREATED, 
                [
                    'resourceType' => 'project',
                    'resourceID' => $project_id,
                    // 'parentType' => null,
                    // 'parentID' => null,
                    'ownerID' => $user_id,
                ]
            ];
    }
}