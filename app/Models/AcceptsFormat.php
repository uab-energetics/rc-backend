<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcceptsFormat extends Model {
    protected $fillable = ['question_id', 'type'];
    public $timestamps = false;
}
