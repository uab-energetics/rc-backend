<?php


namespace App\Services\RabbitMQ;


use App\Messaging\RabbitPublisher;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService {

    public function publishMessage($exchange, $messageData) {
        $this->publisher->publishEvent($exchange, $messageData);
    }

    /** @var RabbitPublisher  */
    protected $publisher;
    /** @var AMQPChannel */
    protected $channel;

    public function __construct(AMQPChannel $channel) {
        $this->publisher = $channel;
        $this->publisher = new RabbitPublisher($channel);
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