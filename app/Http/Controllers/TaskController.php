<?php

namespace App\Http\Controllers;

use App\EncodingTask;
use App\Services\Encodings\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller {

    public function delete(EncodingTask $task, Request $request) {
        DB::beginTransaction();
            $this->service->deleteTask($task);
        DB::commit();
        return okMessage("Successfully deleted task");
    }

    /** @var TaskService  */
    protected $service;

    public function __construct(TaskService $service) {
        $this->service = $service;
    }
}
