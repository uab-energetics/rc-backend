<?php

function getOrDefault(&$var, $default){
    return isset($var)? $var : $default;
}

function invalidParamMessage(\Illuminate\Contracts\Validation\Validator $validator) {
    $reasons = $validator->errors();
    $msg = 'Failed validation';
    if( count($reasons) > 0 ) {
        $msg = $reasons->first();
    }

    return response()->json([
        'status' => 'INVALID_PARAMS',
        'msg' => $msg,
        'reasons' => $reasons
    ], 400);
}