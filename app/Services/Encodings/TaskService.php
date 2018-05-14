<?php


namespace App\Services\Encodings;


use App\EncodingTask;
use App\ProjectEncoding;

class TaskService {

    public function make($params) {
        return EncodingTask::upsert($params);
    }

    public function startEncoding(EncodingTask $task) {
        $form_id = $task->form_id;
        $publication_id = $task->publication_id;
        $user_id = $task->encoder_id;
        $encoding = $this->encodingService->makeEncoding($form_id, $publication_id, $user_id);
        return $encoding;
    }

    public function deleteTasks($tasks) {
        foreach ($tasks as $task) {
            $this->deleteTask($task);
        }
    }

    public function deleteTask(EncodingTask $task){
        $task->delete();
    }


    /** @var EncodingService  */
    protected $encodingService;

    public function __construct(EncodingService $encodingService) {
        $this->encodingService = $encodingService;
    }

}