<?php


namespace App\Traits;


trait SearchableColumns {

    public function toSearchableArray() {
        // Python dictionary comprehensions would be really nice here
        $res = [];
        $model = $this->toArray();
        foreach ($this->searchable as $column) {
            $res[$column] = $model[$column];
        }
        return $res;
    }
}