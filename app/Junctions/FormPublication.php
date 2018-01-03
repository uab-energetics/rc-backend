<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormPublication extends UniqueJunction {
    protected $table = "form_publication";

    protected $fillable = ['project_form_id', 'publication_id', 'priority'];

    /** @return string[] */
    public function uniqueColumns() {
        return ['project_form_id', 'publication_id'];
    }

    public function form() {
        return $this->hasManyThrough(Form::class, ProjectForm::class, 'project_form_id', 'form_id');
    }

    public function project() {
        return $this->hasManyThrough(Project::class, ProjectForm::class, 'project_form_id', 'project_id');
    }

    public function publication() {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    public function projectForm() {
        return $this->belongsTo(ProjectForm::class, 'project_form_id');
    }
}
