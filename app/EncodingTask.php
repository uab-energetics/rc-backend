<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncodingTask extends UniqueJunction {
    protected $table = "encoding_tasks";

    protected $fillable = ['project_form_id', 'encoder_id', 'encoding_id', 'publication_id', 'form_id', 'active', 'complete'];

    protected $appends = ['status'];

    public function encoding() {
        return $this->belongsTo(Encoding::class, 'encoding_id');
    }

    public function projectForm() {
        return $this->belongsTo(ProjectForm::class, 'project_form_id');
    }

    public function encoder() {
        return $this->belongsTo(User::class, 'encoder_id');
    }

    public function publication() {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    public function form() {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function getStatusAttribute() {
        if ($this->encoding_id === null) {
            return TASK_PENDING;
        }
        if ($this->complete === true) {
            return TASK_COMPLETE;
        }
        return TASK_IN_PROGRESS;
    }

    /** @return string[] */
    public function uniqueColumns() {
        return ['project_form_id', 'encoder_id', 'publication_id', 'form_id'];
    }
}
