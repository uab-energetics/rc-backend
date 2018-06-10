<?php

namespace App\Services\RabbitMQ\Handlers;

use App\Services\RabbitMQ\Core\RabbitMessage;
use App\Services\RabbitMQ\Core\RabbitMessageHandler;
use App\Services\Users\UserService;
use Illuminate\Support\Facades\DB;

class ExternalUserCreated implements RabbitMessageHandler {

    /** @var UserService  */
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }


    public function handle(RabbitMessage $message) {
        $params = $message->payload()['user'];
        $params['uuid'] = $params['id'];
        unset($params['id']);

        DB::beginTransaction();
        $email = $params['email'];
        $existing = $this->userService->retrieveByEmail($email);
        if ($existing === null) {
            $this->userService->make($params);
        }
        DB::commit();

        $message->acknowledge();
    }

}
