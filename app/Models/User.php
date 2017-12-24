<?php

namespace App;

use App\Traits\SearchableColumns;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Scout\Searchable;

class User extends Authenticatable {
    use Notifiable;

    use Searchable, SearchableColumns {
        SearchableColumns::toSearchableArray insteadof Searchable;
    }

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
        return $this->hasMany(Encoding::class, 'owner_id');
    }

}
