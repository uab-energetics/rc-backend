<?php


namespace App\Services\Encodings;


use App\Encoding;
use App\EncodingExperimentBranch;
use App\Models\Response;
use Exception;

class EncodingService {

    function recordBranch( $encoding_id, $branch ){
        $encoding = Encoding::find($encoding_id);
        if(!$encoding) return false;

        // get a branch DB model
        $_branch = null;
        if(isset($branch['id']))
            $_branch = EncodingExperimentBranch::find($branch['id']);
        else
            $_branch = new EncodingExperimentBranch();

        // update and save it
        $_branch->fill($branch);
        $encoding->experimentBranches()
            ->save($_branch);

        return EncodingExperimentBranch::find($_branch->id)->toArray();
    }

    function recordResponse( $encoding_id, $branch_id, $response ){
        $encoding = Encoding::find($encoding_id);
        $branch = EncodingExperimentBranch::find($branch_id);
        if(!$encoding || !$branch) return false;

        // get a response DB model
        $_response = null;
        if(isset($response['id']))
            $_response = Response::find($response['id']);
        else
            $_response = new Response();

        // update and save
        $_response->fill($response);
        $branch->responses()->save($_response);

        return Encoding::find($encoding_id)->toArray();
    }

    function deleteBranch($encoding_id, $branch_id){
        $branch = EncodingExperimentBranch::find($branch_id);
        $branch->responses()
            ->delete();
        $branch->delete();
        return Encoding::find($encoding_id)->toArray();
    }

}