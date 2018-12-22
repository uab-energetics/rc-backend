<?php


namespace App\Services\Encodings;


use App\Encoding;
use App\EncodingTask;
use App\Exceptions\TaskAlreadyStartedException;
use App\Form;
use App\ProjectForm;
use App\Publication;
use Illuminate\Database\Query\Builder;

class TaskService {

    public function make($params) {
        return EncodingTask::upsert($params);
    }

    public function dropPendingTasksByProjectForm(ProjectForm $projectForm) {
        $this->pendingTasksByProjectForm($projectForm)
            ->delete();
    }

    public function dropPendingTasksByProjectFormAndPublications(ProjectForm $projectForm, $publication_ids) {
        $this->pendingTasksByProjectForm($projectForm)
            ->whereIn('publication_id', $publication_ids)
            ->delete();
    }

    public function pendingTasksByProjectForm(ProjectForm $projectForm) {
        return $this->getTaskQueryFilter(TASK_PENDING)->filter($projectForm->tasks());
    }

    public function filterTasksByStatus($query, $status = null) {
        $filter = $this->getTaskQueryFilter($status);
        return $filter->filter($query);
    }

    /**
     * @param $query \Illuminate\Database\Eloquent\Builder
     * @param null $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterTasksByKeyword($query, $search = null) {
        if ($search === null) return $query;
        $query = search($query, $search, [], [
            'form' => Form::searchable,
            'publication' => Publication::searchable
        ]);
        return $query;
    }

    public function startEncoding(EncodingTask $task) {
        if ($task->encoding_id !== null) {
            throw new TaskAlreadyStartedException();
        }
        $form_id = $task->form_id;
        $publication_id = $task->publication_id;
        $user_id = $task->encoder_id;
        $encoding = $this->encodingService->makeEncoding($form_id, $publication_id, $user_id);

        $task->encoding_id = $encoding->getKey();
        $task->save();

        return Encoding::find($encoding->id);
    }

    public function updateCompletion(EncodingTask $task, $complete) {
        $task->complete = $complete;
        $task->save();
    }

    public function deleteTasks($tasks) {
        foreach ($tasks as $task) {
            $this->deleteTask($task);
        }
    }

    public function deleteTask(EncodingTask $task){
        $task->delete();
    }

    // Simple factory method. Didn't feel like making a class out of it.
    public function getTaskQueryFilter($status) {
        switch ($status) {
            case TASK_COMPLETE:
                return new CompleteFilter();
            case TASK_IN_PROGRESS:
                return new InProgressFilter();
            case TASK_PENDING:
                return new PendingFilter();
            default:
                return new NullFilter();
        }
    }


    /** @var EncodingService  */
    protected $encodingService;

    public function __construct(EncodingService $encodingService) {
        $this->encodingService = $encodingService;
    }

}