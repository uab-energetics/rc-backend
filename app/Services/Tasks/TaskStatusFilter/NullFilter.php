<?php


namespace App\Services\Encodings;


class NullFilter implements TaskQueryFilter {

    public function filter($query) {
        return $query;
    }
}