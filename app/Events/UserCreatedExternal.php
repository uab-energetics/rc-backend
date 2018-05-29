<?php

namespace App\Events;

use App\Messaging\RabbitMessage;

class UserCreatedExternal {

    /** @var RabbitMessage  */
    public $message;
    public $params;

    public function __construct(RabbitMessage $message) {
        $this->message = $message;
        $params = $message->getPayload()['user'];
        $params['uuid'] = $params['id'];
        unset($params['id']);
        $this->params = $params;
    }
}
