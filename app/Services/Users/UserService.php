<?php


namespace App\Services\Users;


use App\User;

class UserService {

    public function search($query) {
        return User::search($query)->get();
    }

    public function getResearcherProjects(User $user) {
        return $user->researcherProjects()->get();
    }

    public function getCoderProjects(User $user) {
        return $user->researcherProjects()->get();
    }

    public function getEncodings(User $user) {
        return $user->encodings()->get();
    }
}