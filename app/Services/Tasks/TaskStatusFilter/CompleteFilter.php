<?php


namespace App\Services\Encodings;


class CompleteFilter implements TaskQueryFilter {

    public function filter($query) {
        return $query
            ->where('encoding_id', '!=', null)
            ->where('complete', '=', true);
    }
}