<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model {

    protected $fillable = ['question_id', 'type', 'txt', 'num', 'sel', 'boo', 'range_min', 'range_max'];

    protected $with = ['selections'];

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

    public function toAtomic() {
        switch ($this->type) {
            case RESPONSE_MULTI_SELECT:
                $selections = $this->selections()
                    ->orderBy('selections.txt')
                    ->get()->pluck('txt');
                return json_encode( $selections );
            case RESPONSE_RANGE:
                return $this->range_min.":".$this->range_max;
            case RESPONSE_NOT_REPORTED:
                return "Not reported";
            default:
                return $this->getAttribute($this->type);
        }
    }
}
