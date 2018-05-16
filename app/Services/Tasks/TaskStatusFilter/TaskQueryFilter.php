<?php

namespace App\Services\Encodings;


use Illuminate\Database\Eloquent\Builder;

interface TaskQueryFilter {

    /**
     * @param $query Builder
     * @return Builder
     */
    public function filter($query);

}