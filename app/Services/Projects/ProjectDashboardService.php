<?php


namespace App\Services\Projects;


use App\EncodingTask;
use App\Project;

class ProjectDashboardService {

    public function getProjectStats(Project $project) {
        return [
            'users' =>  $this->getUserStats($project),
            'publications' => $this->getPublicationStats($project),
            'codebooks' => $this->getCodebookStats($project),
            'tasks' => $this->getTaskStats($project),
        ];
    }

    public function getUserStats(Project $project) {
        $numEncoders = $project->encoders()->count();
        $numResearchers = $project->researchers()->count();
        $total = $numEncoders + $numResearchers;
        return [
            'encoders' => $numEncoders,
            'researchers' => $numResearchers,
            'total' => $total,
        ];
    }

    public function getPublicationStats(Project $project) {
        return $project->publications()->count();
    }

    public function getCodebookStats(Project $project) {
        return $project->forms()->count();
    }

    public function getTaskStats(Project $project) {
        $query = function () use ($project) {
            return $this->getTasksQuery($project);
        };

        $total = $query()->count();
        $complete = $query()->where('complete', '=', true)->count();
        $in_progress = $query()->where('encoding_id', '!=', null)
            ->where('complete', '=', false)->count();
        $pending = $query()->where('encoding_id', '=', null)->count();

        return [
            'complete' => $complete,
            'active' => $in_progress,
            'pending' => $pending,
            'total' => $total,
        ];
    }

    private function getTasksQuery(Project $project) {
        return EncodingTask::query()
            ->join('project_form', 'encoding_tasks.project_form_id', '=', 'project_form.id')
            ->join('projects', 'project_form.project_id', '=', 'projects.id')
            ->where('projects.id', '=', $project->getKey());
    }


    /** @var ProjectService  */
    protected $projectService;

    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }
}