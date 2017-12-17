<?php
/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 12/17/17
 * Time: 3:39 PM
 */

namespace App\Services\Exports;

abstract class AbstractExportService {

    const NO_RESPONSE = 'NO_RESPONSE';

    /**
     * @param $display
     * @param $key - keys are used for row -> value lookup. They take the form: [ <key_id>, ...<additional params> ]
     * @param $params
     * @return array
     */
    static function header($display, $key, ...$params){
        array_unshift($params, $key);
        return [
            'key' => $params,
            'disp' => $display
        ];
    }

    protected function mapModelToRow($headers, $rowModel){
        $row = [];
        for($i = 0; $i < count($headers); ++$i){
            $cell = FormExportService::NO_RESPONSE;
            $res = $this->lookupValue($rowModel, $headers[$i]);
            if($res) $cell = $res;
            array_push($row, $cell);
        }
        return $row;
    }

    /**
     * We use the header key to dispatch the correct lookup sub-routine.
     * Some of these sub-routines could require additional params (like question -> question ID seen here).
     * This way, we can use any header and lookup anything.
     *
     * @param $rowModel - the object representing the table row.
     * @param $header - the header to look for
     * @return string | null - returns the content of this cell for the given header. If 'false', the 'NO_RESPONSE' constant is used.
     */
    abstract protected function lookupValue($rowModel, $header): string;
}