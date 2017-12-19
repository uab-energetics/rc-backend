<?php


namespace App\Services\Encodings;


use App\Encoding;
use App\EncodingExperimentBranch;
use App\Models\Response;
use Exception;

class EncodingService {

    function createBranch( $encoding_id, $body ){
        $encoding = Encoding::find($encoding_id);
        if(!$encoding) return false;

        $branch = new EncodingExperimentBranch();
        $branch->fill($body);

        $encoding->experimentBranches()
            ->save($branch);

        return Encoding::find($encoding_id);
    }

    function recordResponse( $encoding_id, $branch_id, $body ){
        // delete if exists
        if(isset($body['id']))
            Response::destroy($body['id']);

        $branch = EncodingExperimentBranch::find($branch_id);
        if(!$branch) return false;

        $response = new Response();
        $response->fill($body);
        $branch->responses()->save($response);

        return Encoding::find($encoding_id);
    }
}