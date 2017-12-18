<?php

namespace App\Http\Controllers;

use App\Services\Users\UserService;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function retrieveResearcherProjects(Request $request, UserService $userService) {
        $user = $request->user();
        return $userService->getResearcherProjects($user)->toArray();
    }

    public function retrieveCoderProjects(Request $request, UserService $userService) {
        $user = $request->user();
        return $userService->getCoderProjects($user)->toArray();
    }

}
