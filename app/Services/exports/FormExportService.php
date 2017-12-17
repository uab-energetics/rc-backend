<?php
/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 12/17/17
 * Time: 3:39 PM
 */

namespace App\Services\Exports;


use App\Models\Question;

class FormExportService {

    const NO_RESPONSE = 'NO_RESPONSE';

    /**
     * @param $display
     * @param $key - keys are used for row -> value lookup. They take the form: [ <key_id>, ...<additional params> ]
     * @param $value
     * @return array
     */
    static function header($display, $key, ...$params){
        array_unshift($params, $key);
        return [
            'key' => $params,
            'disp' => $display
        ];
    }

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

    private function mapBranchToRow($headers, $branch){
        $row = [];
        for($i = 0; $i < count($headers); ++$i){
            $cell = FormExportService::NO_RESPONSE;
            $res = $this->lookupResponse($branch, $headers[$i]);
            if($res) $cell = $res;
            array_push($row, $cell);
        }
        return $row;
    }

    private function mapEncodingToRows($headers, $encoding){
        return array_map(function($branch) use ($headers) {
            return $this->mapBranchToRow($headers, $branch);
        }, $encoding['branches']);
    }

    // TODO - to make this service actually work, this method (and only this method) needs implemented
    private function lookupResponse($branch, $header){
        switch ($header['key'][0]){
            case "question":
                return $this->branchGetQuestion($branch, $header['key'][1]);
            case "user":
                return $branch['user_id'];
            default:
                return false;
        }
    }

    // TODO - Demo purposes only. Replace me.
    private function branchGetQuestion( $branch, $question_id ){
        foreach ($branch['responses'] as $response) {
            if($response['qid'] == $question_id){
                return $response['data'];
            }
        }
        return false;
    }
}