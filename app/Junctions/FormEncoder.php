<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormEncoder extends UniqueJunction {
    protected $table = "form_encoder";

    protected $fillable = ['project_form_id', 'encoder_id'];

    /** @return string[] */
    public function uniqueColumns() {
        return ['project_form_id', 'encoder_id'];
    }

    public function form() {
        return $this->hasManyThrough(Form::class, ProjectForm::class, 'project_form_id', 'form_id');
    }

    public function project() {
        return $this->hasManyThrough(Project::class, ProjectForm::class, 'project_form_id', 'project_id');
    }

    public function encoder() {
        return $this->belongsTo(Publication::class, 'encoder_id');
    }

    public function projectForm() {
        return $this->belongsTo(ProjectForm::class, 'project_form_id');
    }
}
