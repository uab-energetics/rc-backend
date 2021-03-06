<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable;


    protected $fillable = [
        'name', 'email', 'password', 'image', 'location', 'website', 'theme', 'bio', 'department'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $searchable = ['name', 'email'];

    public function __construct(array $attributes = []) {
        $this->attributes = [
            'image' => config('custom.default_user_image')
        ];
        parent::__construct($attributes);
    }

    public function researcherProjects() {
        return $this->belongsToMany(Project::class, 'project_researcher', 'researcher_id', 'project_id');
    }

    public function coderProjects() {
        return $this->belongsToMany(Project::class, 'project_coder', 'coder_id', 'project_id');
    }

    public function encodings() {
        return $this->belongsToMany(Encoding::class, 'encoding_tasks', 'encoder_id', 'encoding_id');
    }

    public function tasks() {
        return $this->hasMany(EncodingTask::class, 'encoder_id');
    }

    public function projectFormsEncoder() {
        return $this->belongsToMany(ProjectForm::class, 'form_encoder', 'encoder_id', 'project_form_id');
    }

    const searchable = ['name', 'email'];

}
