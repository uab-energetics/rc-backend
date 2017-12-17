<?php
/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 12/17/17
 * Time: 3:39 PM
 */

namespace App\Services\Exports;

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

    protected function lookupValue($rowModel, $header): string {
        switch ($header['key'][0]){
            case "question":
                // This is an example lookup sub-routine. They could query the database, crunch some numbers, etc.
                // This one requires the question ID as a parameter
                return $this->branchGetQuestion($rowModel, $header['key'][1]);
            case "user":
                return $rowModel['user_id'];
            default:
                return false;
        }
    }

    // TODO - Demo purposes only. Replace me.
    private function branchGetQuestion( $rowModel, $question_id ){
        foreach ($rowModel['responses'] as $response) {
            if($response['qid'] == $question_id){
                return $response['data'];
            }
        }
        return false;
    }
}