<?php

namespace App;

use App\Traits\SearchableColumns;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Publication extends Model {

    protected $table = 'publications';

    protected $fillable = ['name', 'source_id', 'embedding_url'];

    const searchable = ['name', 'source_id', 'embedding_url'];

}
