<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectForm extends UniqueJunction {
    protected $table = "project_form";

    protected $fillable = ['project_id', 'form_id', 'task_target_encoder', 'task_target_publication', 'auto_enroll'];

    /** @return string[] */
    public function uniqueColumns() {
        return ['project_id', 'form_id'];
    }

    public function formPublications() {
        return $this->hasMany(FormPublication::class, 'project_form_id')->with('publication');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function form() {
        return $this->belongsTo(Form::class, 'form_id')->without('rootCategory');
    }

    /** @return BelongsToMany */
    public function publications() {
        return $this->belongsToMany(Publication::class, 'form_publication', 'project_form_id', 'publication_id')
            ->withPivot('priority');
    }

    public function encoders() {
        return $this->belongsToMany(User::class, 'form_encoder', 'project_form_id', 'encoder_id');
    }

    public function tasks() {
        return $this->hasMany(EncodingTask::class, 'project_form_id');
    }

    public static function publicationsSearchable() {
        $result = Publication::searchable;
        $result[] = 'priority';
        return $result;
    }
}
