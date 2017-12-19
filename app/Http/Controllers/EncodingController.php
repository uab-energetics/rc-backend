<?php

namespace App\Http\Controllers;

use App\Services\Encodings\EncodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EncodingController extends Controller {

    private $rules = [
        'branch_exists' => [ 'branch_id' => 'required|exists:encoding_experiment_branches,id' ],
        'encoding_exists' => [ 'encoding_id' => 'required|exists:encodings,id' ]
    ];

    // TODO - use dependency injection
    private $service;

    public function __construct() {
        $this->service = new EncodingService();
    }

    function recordBranch(Request $request){
        $rules = array_merge(
            [ 'branch' => 'required' ],
            $this->rules['encoding_exists']
        );
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
            return response()->json($validator->errors(), 400);

        $result = $this->service->recordBranch($request->encoding_id, $request->branch);
        if($result) return response()->json($result, 200);
        return response("couldn't record branch", 500);
    }

    function recordResponse(Request $request){
        $rules = array_merge(
            $this->rules['encoding_exists'],
            $this->rules['branch_exists'],
            [ 'response' => 'required' ]
        );
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
            return response()->json($validator->errors(), 400);

        /* valid. attempting operation */
        $result = $this->service->recordResponse(
            $request->encoding_id,
            $request->branch_id,
            $request->response
        );
        if($result) return response()->json($result, 200);
        return response("couldn't record response", 500);
    }

    function deleteBranch(Request $request){
        $rules = array_merge(
            $this->rules['encoding_exists'],
            $this->rules['branch_exists']
        );
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
            return response()->json($validator->errors(), 400);

        /* valid. attempting operation */
        $result = $this->service->deleteBranch(
            $request->encoding_id,
            $request->branch_id
        );
        if($result) return response()->json($result, 200);
        return response("couldn't delete branch", 500);
    }

}
