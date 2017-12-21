<?php


namespace App\Services\Encodings;


use App\Encoding;
use App\EncodingExperimentBranch;
use App\Models\Response;
use Exception;

class EncodingService {

    function recordBranch( $encoding_id, $branch ){
        $encoding = Encoding::find($encoding_id);
        if(!$encoding || !$branch) return false;

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

    public function dispatch( $encoding_action ){
        $result = false;
        switch ($encoding_action['type']){
            case EncodingActions::RECORD_BRANCH:
                $result = $this->recordBranch(
                    $encoding_action['encoding_id'],
                    $encoding_action['branch']
                );
                break;
            case EncodingActions::RECORD_RESPONSE:
                $result = $this->recordResponse(
                    $encoding_action['encoding_id'],
                    $encoding_action['branch_id'],
                    $encoding_action['response']
                );
                break;
            case EncodingActions::DELETE_BRANCH:
                $result = $this->deleteBranch(
                    $encoding_action['encoding_id'],
                    $encoding_action['branch_id']
                );
                break;
        }
        return $result;
    }

    public function dispatchAll( $encoding_actions ){
        $results = [];
        foreach ($encoding_actions as $action){
            $results[] = $this->dispatch($action);
        }
        return $results;
    }
}