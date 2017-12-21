<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Selections extends Model {
    protected $fillable = ['response_id', 'option_id', 'txt'];

    public $timestamps = false;
}
