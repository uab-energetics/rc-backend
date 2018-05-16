<?php


namespace App\Services\Projects;


use App\EncodingTask;
use App\Project;
use App\Services\Encodings\TaskService;

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
            'encoder_cnt' => $numEncoders,
            'researcher_cnt' => $numResearchers,
            'encoders' => $project->encoders()->get(),
            'researchers' => $project->researchers()->get(),
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
        $query = function ($status = null) use ($project) {
            $query = $this->getTasksQuery($project);
            return $this->filterTasksQuery($query, $status);
        };

        $total = $query()->count();
        $complete = $query(TASK_COMPLETE)->count();
        $in_progress = $query(TASK_IN_PROGRESS)->count();
        $pending = $query(TASK_PENDING)->count();

        return [
            TASK_COMPLETE => $complete,
            TASK_IN_PROGRESS => $in_progress,
            TASK_PENDING => $pending,
            'total' => $total,
        ];
    }

    private function getTasksQuery(Project $project) {
        return EncodingTask::query()
            ->join('project_form', 'encoding_tasks.project_form_id', '=', 'project_form.id')
            ->join('projects', 'project_form.project_id', '=', 'projects.id')
            ->where('projects.id', '=', $project->getKey());
    }

    private function filterTasksQuery($query, $status) {
        return $this->taskService->filterTasksByStatus($query, $status);
    }


    /** @var ProjectService  */
    protected $projectService;
    /** @var TaskService  */
    protected $taskService;

    public function __construct(ProjectService $projectService, TaskService $taskService) {
        $this->projectService = $projectService;
        $this->taskService = $taskService;
    }
}