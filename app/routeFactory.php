<?php

function getter( $model_class ){
    return function(Request $request, $id) use ( $model_class ) {
        $model = call_user_func("$model_class::find", $id);
        if(!$model) abort(404);
        return $model;
    };
}