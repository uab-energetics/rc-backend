<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectForm extends UniqueJunction {
    protected $table = "project_form";

    protected $fillable = ['project_id', 'form_id', 'task_target_encoder', 'task_target_publication'];

    /** @return string[] */
    public function uniqueColumns() {
        return ['project_id', 'form_id'];
    }

    public function formPublications() {
        return $this->hasMany(FormPublication::class, 'project_form_id')->with('publication');
    }

    public function publications() {
        return $this->belongsToMany(Publication::class, 'form_publication', 'project_form_id', 'publication_id');
    }

    public function encoders() {
        return $this->belongsToMany(User::class, 'form_encoder', 'project_form_id', 'encoder_id');
    }
}
