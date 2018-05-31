<?php

namespace App\Http\Controllers;

use App\Services\Users\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserChannelController extends Controller {

    public function created(Request $request) {
        $request->validate(static::$createRules);
        DB::beginTransaction();
            $this->userService->make($request->all());
        DB::commit();
        return okMessage(null, 201);
    }

    public function updated(Request $request) {
        $request->validate(static::$updateRules);
        DB::beginTransaction();
            $user = $this->userService->retrieveByUuidOrFail($request->uuid);
            $this->userService->update($user, $request->all());
        DB::commit();
        return okMessage();
    }

    public function deleted(Request $request) {
        $request->validate(static::$deleteRules);
        DB::beginTransaction();
            $user = $this->userService->retrieveByUuidOrFail($request->uuid);
            $this->userService->delete($user);
        DB::commit();
        return okMessage();
    }


    /** @var UserService  */
    protected $userService;

    public function __construct(UserService $service) {
        $this->userService = $service;
    }

    public static $createRules = [
        'uuid' => 'string|required|unique:users,uuid',
        'name' => 'string|required',
        'email' => 'email|required|unique:users,email',
        'image' => 'url|required',
        'location' => 'string|nullable',
        'bio' => 'string|nullable',
        'website' => 'url|nullable',
        'department' => 'string|nullable',
        'theme' => 'string|nullable',
    ];

    public static $updateRules = [
        'uuid' => 'required|string|exists:users,uuid',
        'name' => 'string',
        'email' => 'string',
        'image' => 'url',
        'location' => 'string|nullable',
        'bio' => 'string|nullable',
        'website' => 'url|nullable',
        'theme' => 'string|nullable',
    ];

    public static $deleteRules = [
        'uuid' => 'required|string|exists:users,uuid',
    ];

}
