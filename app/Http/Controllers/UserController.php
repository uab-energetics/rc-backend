<?php

namespace App\Http\Controllers;

use App\Events\UserUpdated;
use App\Rules\TaskStatus;
use App\Services\Users\UserService;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function search(Request $request, UserService $userService) {
        $validator = simpleSearchValidator($request->all());
        if ($validator->fails()) return invalidParamMessage($validator);
        return $userService->search($request->search);
    }

    public function retrieveResearcherProjects(Request $request, UserService $userService) {
        $user = $request->user();
        return $userService->getResearcherProjects($user);
    }

    public function retrieveCoderProjects(Request $request, UserService $userService) {
        $user = $request->user();
        return $userService->getCoderProjects($user);
    }

    public function retrieveEncodings(Request $request, UserService $userService) {
        $user = $request->user();
        return $userService->getEncodings($user);
    }

    public function retrieveTasks(Request $request, UserService $userService) {
        $request->validate([
            'status' => ['nullable', new TaskStatus()],
            'search' => 'nullable|string',
        ]);
        $user = $request->user();
        return paginate($userService->getTasks($user, $request->status, $request->search));
    }

    public function retrieveForms(Request $request, UserService $userService) {
        $user = $request->user();
        return $userService->getFormsEncoder($user);
    }

    public function updateProfile(Request $request, UserService $userService){
        $user = $request->user();
        return $userService->update($user, $request->all());
    }

}
