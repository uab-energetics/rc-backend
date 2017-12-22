<?php

namespace App;

use App\Traits\SearchableColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Form extends Model {

    use Searchable, SearchableColumns {
        SearchableColumns::toSearchableArray insteadof Searchable;
    }

    use SoftDeletes;

    protected $fillable = ['root_category_id', 'name', 'description', 'published', 'type'];

    protected $with = ['rootCategory'];

    protected $searchable = ['name', 'description', 'type', 'published'];

    function rootCategory(){
        return $this->belongsTo(Category::class, 'root_category_id');
    }
}
