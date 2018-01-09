<?php

namespace App;

use App\Traits\SearchableColumns;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Publication extends Model {

    protected $table = 'publications';

    protected $fillable = ['name', 'embedding_url'];

    const searchable = ['name', 'embedding_url'];

}
