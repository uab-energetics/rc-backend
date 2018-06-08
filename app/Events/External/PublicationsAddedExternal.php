<?php

namespace App\Events\External;

use App\Messaging\RabbitMessage;

class PublicationsAddedExternal {

    /** @var RabbitMessage  */
    public $message;
    public $repo_id;
    public $publications;

    public function __construct(RabbitMessage $message) {
        $this->message = $message;
        $params = $message->getPayload();
        $this->repo_id = $params['repoID'];
        $this->publications = $params['publications'];
    }

}
