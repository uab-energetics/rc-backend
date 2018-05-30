<?php

namespace App;

use App\Traits\SearchableColumns;
use Illuminate\Database\Eloquent\Model;

class Project extends Model {


    protected $table = "projects";

    protected $fillable = ['name', 'description'];

    protected $searchable = self::searchable;

    const searchable = ['name', 'description'];

    public function forms() {
        return $this->belongsToMany(Form::class, 'project_form', 'project_id', 'form_id');
    }

    public function publications() {
        return $this->belongsToMany(Publication::class, 'project_publication', 'project_id', 'publication_id');
    }

    public function researchers(){
        return $this->belongsToMany(User::class, 'project_researcher', 'project_id', 'researcher_id');
    }

    public function encoders(){
        return $this->belongsToMany(User::class, 'project_coder', 'project_id', 'coder_id');
    }

}
