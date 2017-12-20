<?php

namespace App;

use App\Traits\SearchableColumns;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Publication extends Model {

    use Searchable, SearchableColumns {
        SearchableColumns::toSearchableArray insteadof Searchable;
    }

    protected $table = 'publications';

    protected $fillable = ['name', 'embedding_url'];

    protected $searchable = ['name'];

}
