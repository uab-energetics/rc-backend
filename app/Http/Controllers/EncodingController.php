<?php

namespace App\Http\Controllers;

use App\Encoding;
use App\EncodingExperimentBranch as Branch;
use App\Rules\ResponseType;
use App\Services\Encodings\EncodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EncodingController extends Controller {

    function createBranch(Encoding $encoding, Request $request, EncodingService $encodingService){
        $validator = $this->branchValidator($request->all());
        if($validator->fails())  return invalidParamMessage($validator);

        $result = $encodingService->recordBranch($encoding->getKey(), $request->all());

        if($result) return $result;
        return response("couldn't record branch", 500);
    }

    function createBranchResponse(Encoding $encoding, Branch $branch, Request $request, EncodingService $encodingService){
        $validator = $this->responseValidator($request->all());
        if($validator->fails()) return invalidParamMessage($validator);

        /* valid. attempting operation */
        $result = $encodingService->recordResponse(
            $encoding->getKey(),
            $branch->getKey(),
            $request->all()
        );
        if($result) return $result;
        return response("couldn't record response", 500);
    }

    function deleteBranch(Encoding $encoding, Branch $branch, EncodingService $encodingService) {
        $encodingService->deleteBranch(
            $encoding->getKey(),
            $branch->getKey()
        );
        return $encoding->refresh();
    }

    protected function branchValidator($data) {
        return Validator::make($data, [
            'id' => 'exists:encoding_experiment_branches',
            'encoding_id' => 'exists:encodings,id',
            'name' => 'required|string',
            'description' => 'string',
        ]);
    }

    protected function responseValidator($data) {
        return Validator::make($data, [
            'id' => 'exists:responses',
            'question_id' => 'exists:questions,id',
            'type' => new ResponseType(),
            RESPONSE_TEXT => 'string|required_if:type,'.RESPONSE_TEXT,
            RESPONSE_NUMBER => 'number|required_if:type,'.RESPONSE_NUMBER,
            RESPONSE_BOOL => 'boolean|required_if:type,'.RESPONSE_BOOL,
            RESPONSE_RANGE.'_min' => 'number|required_if:type,'.RESPONSE_RANGE,
            RESPONSE_RANGE.'_max' => 'number|required_if:type,'.RESPONSE_RANGE,
            RESPONSE_SELECT => 'string|required_if:type,'.RESPONSE_SELECT.'|required_if:type,'.RESPONSE_MULTI_SELECT
        ]);
    }

}
