<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    protected $table = "projects";

    protected $fillable = [
        'name',
        'description',
    ];

    public function forms() {
        return $this->belongsToMany(Form::class, 'project_form', 'project_id', 'form_id');
    }

    public function publications() {
        return $this->belongsToMany(Publication::class, 'project_publication', 'project_id', 'publication_id');
    }
}
