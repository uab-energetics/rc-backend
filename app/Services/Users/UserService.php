<?php


namespace App\Services\Users;


use App\User;

class UserService {

    public function retrieve($user_id) {
        return User::findOrFail($user_id);
    }

    public function search($query) {
        return User::search($query)->paginate(getPaginationLimit())->toArray()['data'];
    }

    public function getResearcherProjects(User $user) {
        return $user->researcherProjects()->get();
    }

    public function getCoderProjects(User $user) {
        return $user->researcherProjects()->get();
    }

    public function getEncodings(User $user) {
        return $user->encodings()
            ->with(['publication', 'form' => function ($query) {
                $query->without('rootCategory');
            }])
            ->get();
    }

    public function getFormsEncoder(User $user) {
        return $user->projectFormsEncoder()
            ->with('form')
            ->get()
            ->pluck('form');
    }
}