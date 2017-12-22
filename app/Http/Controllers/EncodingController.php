<?php

namespace App\Http\Controllers;

use App\Encoding;
use App\EncodingExperimentBranch as Branch;
use App\Rules\ResponseType;
use App\Services\Encodings\EncodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EncodingController extends Controller {

    public function retrieve(Encoding $encoding) {
        return $encoding;
    }

    public function update(Encoding $encoding, Request $request, EncodingService $encodingService) {
        $params = $request->all();
        $validator = $this->updateValidator($params);
        if ($validator->fails()) return invalidParamMessage($validator);

        DB::beginTransaction();
            $encodingService->updateEncoding($encoding, $params);
        DB::commit();

        return $encoding->refresh();
    }

    public function delete(Encoding $encoding, EncodingService $encodingService) {
        DB::beginTransaction();
            $encodingService->deleteEncoding($encoding);
        DB::commit();
        return okMessage("Successfully deleted encoding");
    }

    public function createBranch(Encoding $encoding, Request $request, EncodingService $encodingService){
        $validator = $this->branchValidator($request->all());
        if($validator->fails())  return invalidParamMessage($validator);

        $result = $encodingService->recordBranch($encoding->getKey(), $request->all());

        if($result) return $result;
        return response("couldn't record branch", 500);
    }

    public function createBranchResponse(Encoding $encoding, Branch $branch, Request $request, EncodingService $encodingService){
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

    public function deleteBranch(Encoding $encoding, Branch $branch, EncodingService $encodingService) {
        $encodingService->deleteBranch(
            $encoding->getKey(),
            $branch->getKey()
        );
        return $encoding->refresh();
    }

    protected function updateValidator($data) {
        return Validator::make($data, []);
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
