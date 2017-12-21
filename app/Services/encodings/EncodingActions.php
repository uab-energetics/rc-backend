<?php
/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 12/19/17
 * Time: 3:55 PM
 */

namespace App\Services\Encodings;


class EncodingActions
{
    const RECORD_BRANCH = 'rec-branch';
    const RECORD_RESPONSE = 'rec-res';
    const DELETE_BRANCH = 'del-branch';

    static function recordBranch($encoding_id, $branch){
        return [
            'type' => EncodingActions::RECORD_BRANCH,
            'encoding_id' => $encoding_id,
            'branch' => $branch
        ];
    }

    static function recordResponse($encoding_id, $branch_id, $response){
        return [
            'type' => EncodingActions::RECORD_RESPONSE,
            'encoding_id' => $encoding_id,
            'branch_id' => $branch_id,
            'response' => $response
        ];
    }

    static function deleteBranch($encoding_id, $branch_id){
        return [
            'type' => EncodingActions::DELETE_BRANCH,
            'encoding_id' => $encoding_id,
            'branch_id' => $branch_id
        ];
    }
}