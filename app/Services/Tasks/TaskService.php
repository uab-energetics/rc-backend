<?php


namespace App\Services\Encodings;


use App\EncodingTask;
use App\Exceptions\TaskAlreadyStartedException;
use App\ProjectEncoding;

class TaskService {

    public function make($params) {
        return EncodingTask::upsert($params);
    }

    public function filterTasksByStatus($query, $status = null) {
        $filter = $this->getTaskQueryFilter($status);
        return $filter->filter($query);
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

        return $encoding;
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