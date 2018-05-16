<?php


namespace App\Services\Encodings;


class PendingFilter implements TaskQueryFilter {

    public function filter($query) {
        return $query
            ->where('encoding_id', '=', null);
    }
}