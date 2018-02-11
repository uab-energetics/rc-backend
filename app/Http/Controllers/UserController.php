<?php

namespace App\Http\Controllers;

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
        $user = $request->user();
        return $userService->getTasks($user);
    }

    public function retrieveForms(Request $request, UserService $userService) {
        $user = $request->user();
        return $userService->getFormsEncoder($user);
    }

    public function updateProfile(Request $request){
        $user = $request->user();
        $user->fill($request->all());
        $user->save();
        return User::find($user->getKey());
    }

}
