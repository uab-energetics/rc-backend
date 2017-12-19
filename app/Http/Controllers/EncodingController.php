<?php

namespace App\Http\Controllers;

use App\Encoding;
use App\EncodingExperimentBranch as Branch;
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

    function deleteBranch(Encoding $encoding, Branch $branch, Request $request, EncodingService $encodingService) {
        /* valid. attempting operation */
        $result = $encodingService->deleteBranch(
            $encoding->getKey(),
            $branch->getKey()
        );
        if($result) return $result;
        return response("couldn't delete branch", 500);
    }

    protected function branchValidator($data) {
        return Validator::make($data, [

        ]);
    }

    protected function responseValidator($data) {
        return Validator::make($data, [

        ]);
    }

}
