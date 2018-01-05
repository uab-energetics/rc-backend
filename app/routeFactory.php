<?php

function getter( $model_class ){
    return function($id) use ( $model_class ) {
        return $model_class::findOrFail($id);
    };
}

function searcher( $model_class ){
    return function() use ($model_class) {
        $query = $model_class::query();
        $page_size = request('page_size', 500);
        $search = request('search');
        if($search && $model_class::searchable)
            foreach ($model_class::searchable as $column)
                $query = $query->orWhere($column, 'like', "%$search%");
        return $query->paginate($page_size);
    };
}