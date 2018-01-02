<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

abstract class UniqueJunction extends Model {

    /** @return string[] */
    public abstract function uniqueColumns();

    //This would ideally use the same pattern that Eloquent models use,
    // but __callStatic() wasn't working as expected.
    public static function upsert($params) {
        return (new static)->doUpsert($params);
    }

    public function doUpsert($params) {
        $columns = static::uniqueColumns();
        $existing = $this->findByExample($columns, $params);
        if ($existing !== null) {
            $existing->update($params);
            return $existing;
        }
        $new = parent::create($params);
        return $new;
    }

    protected function findByExample($columns, $params) {
        $query = static::query();
        foreach ($columns as $column) {
            $query->where($column, '=', $params[$column]);
        }
        return $query->first();
    }
}