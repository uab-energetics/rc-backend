<?php

namespace App\Listeners\External;

use App\Events\External\UserCreatedExternal;
use App\Services\Users\UserService;
use Illuminate\Support\Facades\DB;

class UserCreatedExternalListener {

    /** @var UserService  */
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }


    public function handle(UserCreatedExternal $event) {
        $params = $event->params;
        DB::beginTransaction();
            $email = $params['email'];
            $existing = $this->userService->retrieveByEmail($email);
            if ($existing === null) {
                $this->userService->make($params);
            }
        DB::commit();
        $event->message->ack();
    }
}
