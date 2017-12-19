<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'image'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

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
        return $this->hasMany(Encoding::class, 'owner_id');
    }

}
