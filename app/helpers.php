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

function okMessage($message, $code = 200, $extra = []) {
    $arr = [
        'status' => 'ok',
        'msg' => $message
    ];
    $arr += $extra;
    return response()->json($arr, $code);
}

function simpleSearchValidator($data) {
    return \Illuminate\Support\Facades\Validator::make($data, [
        'search' => 'string|nullable'
    ]);
}

function batchUnset(&$array, array $keys) {
    foreach ($keys as $key) {
        if (isset($array[$key])) {
            unset($array[$key]);
        }
    }
}

function getStreamWriter($headers, $rows) {
    return function() use (&$headers, &$rows) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $headers);
        foreach($rows as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    };
}

function csvResponseHeaders($file_name ){
    return [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$file_name.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];
}

function getPaginationLimit(){
    return min(config('custom.pagination_max_size', 500), request('page_size', 500));
}

/**
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @param string $term
 * @param string[] $columns
 * @return \Illuminate\Database\Eloquent\Builder
 */
function search($query, $term, $columns){
    if(!$term) return $query;
    $query->where($columns[0], 'like', "%$term%");
    for($i = 1; $i < count($columns); $i++)
        $query->orWhere($columns[$i], 'like', "%$term%");
    return $query;
}