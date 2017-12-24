<?php

namespace App\Http\Controllers;

use App\Encoding;
use App\EncodingExperimentBranch as Branch;
use App\Events\EncodingChanged;
use App\Rules\ResponseType;
use App\Services\Encodings\EncodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EncodingController extends Controller {

    public function retrieve(Encoding $encoding) {
        return $encoding;
    }

    public function update(Encoding $encoding, Request $request, EncodingService $encodingService) {

        DB::beginTransaction();
            $encodingService->updateEncoding($encoding, $request->all());
        DB::commit();

        return $encoding->refresh();
    }

    public function delete(Encoding $encoding, EncodingService $encodingService) {
        DB::beginTransaction();
            $encodingService->deleteEncoding($encoding);
        DB::commit();
        return okMessage("Successfully deleted encoding");
    }


    // TODO - rename these 'create' methods to 'upsert', 'record', or something more accurate

    public function createBranch(Encoding $encoding, Request $request, EncodingService $encodingService){
        $request->validate([
            'id' => 'exists:encoding_experiment_branches',
            'encoding_id' => 'exists:encodings,id',
            'name' => 'required|string',
            'description' => 'string',
        ]);

        $result = $encodingService->recordBranch($encoding->getKey(), $request->all());

        event(new EncodingChanged($encoding->id));

        if($result) return $result;
        return response("couldn't record branch", 500);
    }

    public function createBranchResponse(Encoding $encoding, Branch $branch, Request $request, EncodingService $encodingService){

        $request->validate([
            'id' => 'exists:responses',
            'question_id' => 'exists:questions,id',
            'type' => new ResponseType(),
            RESPONSE_TEXT => 'nullable|string|required_if:type,'.RESPONSE_TEXT,
            RESPONSE_NUMBER => 'nullable|numeric|required_if:type,'.RESPONSE_NUMBER,
            RESPONSE_BOOL => 'nullable|string|required_if:type,'.RESPONSE_BOOL,
            RESPONSE_RANGE.'_min' => 'nullable|numeric|required_if:type,'.RESPONSE_RANGE,
            RESPONSE_RANGE.'_max' => 'nullable|numeric|required_if:type,'.RESPONSE_RANGE,
            RESPONSE_SELECT => 'nullable|string|required_if:type,'.RESPONSE_SELECT,
            'selections' => 'nullable|required_if:type,'.RESPONSE_MULTI_SELECT,
            'selections.*.txt' => 'required|distinct',
        ]);

        /* valid. attempting operation */
        $result = $encodingService->recordResponse(
            $encoding->getKey(),
            $branch->getKey(),
            $request->all()
        );

        event(new EncodingChanged($encoding->id));

        if($result) return $result;
        return response("couldn't record response", 500);
    }

    public function deleteBranch(Encoding $encoding, Branch $branch, EncodingService $encodingService) {
        $encodingService->deleteBranch(
            $encoding->getKey(),
            $branch->getKey()
        );

        event(new EncodingChanged($encoding->id));

        return $encoding->refresh();
    }

}
