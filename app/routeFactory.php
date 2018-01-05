<?php

function getter( $model_class ){
    return function($id) use ( $model_class ) {
        return $model_class::findOrFail($id);
    };
}

function searcher( $model_class ){
    return function() use ($model_class) {
        $page_size = request('page_size', 500);
        $search = request('search');
        $query = $model_class::query();
        if($search && $model_class::searchable)
            $query = search($query, $search, $model_class::searchable);
        return $query->paginate($page_size);
    };
}