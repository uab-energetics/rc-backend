<?php

namespace App;

use App\Models\Question;
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

    protected $with = ['rootCategory', 'questions'];

    protected $searchable = ['name', 'description', 'type', 'published'];

    public function rootCategory(){
        return $this->belongsTo(Category::class, 'root_category_id');
    }

    public function questions() {
        return $this->belongsToMany(Question::class, 'form_question', 'form_id', 'question_id')
            ->orderBy('name');
    }

    public function encodings() {
        return $this->hasMany(Encoding::class, 'form_id');
    }
}
