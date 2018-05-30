<?php

namespace App;

use App\Traits\SearchableColumns;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model {

    protected $table = 'publications';

    protected $fillable = ['name', 'source_id', 'embedding_url'];

    const searchable = ['name', 'source_id', 'embedding_url'];

}
