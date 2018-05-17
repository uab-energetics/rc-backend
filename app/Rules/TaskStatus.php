<?php

namespace App\Rules;

class TaskStatus extends Enum {
    protected $validTypes = [
        TASK_PENDING,
        TASK_IN_PROGRESS,
        TASK_COMPLETE,
    ];

    /** Create a new rule instance.
     * @return void
     */
    public function __construct() {
        parent::__construct($this->validTypes);
    }
}
