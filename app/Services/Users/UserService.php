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
            ->without(['experimentBranches', 'simpleResponses'])
            ->with(['publication', 'form' => function ($query) {
                $query->without(['rootCategory', 'questions']);
            }])
            ->get();
    }

    public function getTasks(User $user) {
        return $user->tasks()
            ->with(['encoding' => function($query) {
                $query->without(['experimentBranches', 'simpleResponses']);
                $query->with(['publication', 'form' => function ($subquery) {
                    $subquery->without(['rootCategory', 'questions']);
                }]);
            }])
            ->get();
    }

    public function getFormsEncoder(User $user) {
        return $user->projectFormsEncoder()
            ->with(['form' => function ($query) {
                $query->without(['questions', 'rootCategory']);
                $query->withTrashed();
            },
                    'project'])
            ->get();
    }
}