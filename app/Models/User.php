<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable;

    /** The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /** The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function researcherProjects() {
        return $this->belongsToMany(Project::class, 'project_researcher', 'researcher_id', 'project_id');
    }

    public function coderProjects() {
        return $this->belongsToMany(Project::class, 'project_coder', 'coder_id', 'project_id');
    }

}
