<?php

namespace App\Http\Controllers;

use App\EncodingTask;
use App\Services\Encodings\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller {

    public function startEncoding(EncodingTask $task) {
        DB::beginTransaction();
            $encoding = $this->service->startEncoding($task);
        DB::commit();
        return okMessage("Successfully started encoding", 201, [
            'encoding' => $encoding
        ]);
    }

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
