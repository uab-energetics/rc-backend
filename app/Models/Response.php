<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model {

    protected $fillable = ['question_id', 'type', 'txt', 'num', 'sel', 'boo'];

    function selections(){
        return $this->hasMany(Selections::class, 'response_id');
    }

    function saveSelections( $selections_arr ){
        parent::save();
        $this->selections()->delete();
        $this->selections()->createMany($selections_arr);
    }

    public static function createWithSelections($data){
        $selections = getOrDefault($data['selections'], []);
        $response = Response::create($data);
        $response->saveSelections($selections);
        return $response;
    }
}
