<?php

namespace App\Services\Exports;

use App\EncodingExperimentBranch;
use App\Models\Response;

class FormExportService extends AbstractExportService {

    public function exportFormData($headers, $form){
        $rows = [];
        foreach( $form['encodings'] as $encoding ){
            $rows = array_merge($rows, $this->mapEncodingToRows($headers, $encoding));
        }
        $pretty_headers = array_map(function($header){
            return $header['disp'];
        }, $headers);

        array_unshift($rows, $pretty_headers); // push display headers row to front
        return $rows;
    }

    private function mapEncodingToRows($headers, $encoding){
        return array_map(function($rowModel) use ($headers) {
            return $this->mapModelToRow($headers, $rowModel);
        }, $encoding['branches']);
    }

    protected function lookupValue($rowModel, $header) {
        switch ($header['key'][0]){
            case "branch":
                return $rowModel['name'];
            case "question":
                return $this->branchGetResponse($rowModel, $header['key'][1]);
            case "publication":
                return $this->branchGetPublication($rowModel['id'])['name'];
            case "user_id":
                return $this->branchGetUser($rowModel['id'])['id'];
            case "user_name":
                return $this->branchGetUser($rowModel['id'])['name'];
            default:
                return false;
        }
    }

    private function branchGetUser($branch_id) {
        $branch = EncodingExperimentBranch::find($branch_id);
        if ($branch === null) return false;
        return $branch->encoding->owner->toArray();
    }

    private function branchGetResponse( $rowModel, $question_id ){
        $branch = EncodingExperimentBranch::find($rowModel['id']);
        if ($branch === null) return false;
        /** @var Response $response */
        $response = $branch->responses()->where('question_id', '=', $question_id)->first();
        if ($response === null) return false;
        return $response->toAtomic();
    }

    private function branchGetPublication($branch_id) {
        $branch = EncodingExperimentBranch::find($branch_id);
        if ($branch === null) return false;
        return $branch->encoding->publication->toArray();
    }
}