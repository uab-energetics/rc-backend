<?php


namespace App\Services\Encodings;


class InProgressFilter implements TaskQueryFilter {

    public function filter($query) {
        return $query
            ->where('encoding_id', '!=', null)
            ->where('complete', '=', false);
    }
}