<?php

namespace App\Events\External;

use App\Messaging\RabbitMessage;

class PublicationsRemovedExternal {

    /** @var RabbitMessage  */
    public $message;
    public $repo_id;
    public $publication_ids;

    public function __construct(RabbitMessage $message) {
        $this->message = $message;
        $params = $message->getPayload();
        $this->repo_id = $params['repoID'];
        $this->publication_ids = $params['publicationIDs'];
    }

}
