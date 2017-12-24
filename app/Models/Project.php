<?php

namespace App;

use App\Traits\SearchableColumns;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Project extends Model {

    use Searchable, SearchableColumns {
        SearchableColumns::toSearchableArray insteadof Searchable;
    }

    protected $table = "projects";

    protected $fillable = ['name', 'description'];

    protected $searchable = ['name', 'description'];

    public function forms() {
        return $this->belongsToMany(Form::class, 'project_form', 'project_id', 'form_id');
    }

    public function publications() {
        return $this->belongsToMany(Publication::class, 'project_publication', 'project_id', 'publication_id');
    }

    public function researchers(){
        return $this->belongsToMany(User::class, 'project_researcher', 'project_id', 'researcher_id');
    }
}
