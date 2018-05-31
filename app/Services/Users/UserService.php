<?php


namespace App\Services\Users;


use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Events\UserDeleted;
use App\Services\Encodings\TaskService;
use App\User;

class UserService {

    public function retrieve($user_id) {
        return User::findOrFail($user_id);
    }

    public function retrieveByUuid($uuid) {
        return User::query()
            ->where('uuid', '=', $uuid)
            ->first();
    }

    public function retrieveByUuidOrFail($uuid) {
        return User::query()
            ->where('uuid', '=', $uuid)
            ->firstOrFail();
    }

    public function search($search) {
        $query = User::query();
        return search($query, $search, User::searchable);
    }

    public function make($params) {
        $user = new User();
        $user->uuid = $params['uuid'];
        $user->fill($params);
        $user->save();
        $user = $user->refresh();
        event(new UserCreated($user));
        return $user;
    }

    public function update(User $user, $params) {
        $user->update($params);
        $user = $user->refresh();
        event(new UserUpdated($user));
        return $user;
    }

    public function delete(User $user) {
        event(new UserDeleted($user));
        $user->delete();
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

    public function getTasks(User $user, $status = null, $search = null) {
        $query = $user->tasks()
            ->with([
                'encoding' => function($query) {
                    $query->without(['experimentBranches', 'simpleResponses']);
                },
                'form' => function ($query) {
                    $query->without(['rootCategory', 'questions']);
                },
                'publication'
            ]);
        $filtered = $this->taskService->filterTasksByStatus($query, $status);
        $searched = $this->taskService->filterTasksByKeyword($query, $search);
        return $searched;
    }

    public function getFormsEncoder(User $user) {
        return $user->projectFormsEncoder()
            ->with(['form' => function ($query) {
                $query->without(['questions', 'rootCategory']);
            },
                    'project'])
            ->get();
    }

    /** @var TaskService  */
    protected $taskService;

    public function __construct(TaskService $taskService) {
        $this->taskService = $taskService;
    }
}