<?php


namespace App\Services\Publications;


use App\Publication;

/**
 * Array should be of the format:
 * [ <publication_name>, <embedding_url> ]
 *
 */
class CsvUploadService {

    // this will look like the fillable array of the eloquent model
    const HEADERS = [ 'name', 'embedding_url' ];

    public $fail_message;

    public function parse($matrix){
        if(!$this->validateMatrix($matrix))
            return false;
        $records = [];
        foreach( $matrix as $row ){
            if(!$this->validateRow($row))
                return false;

            $record = [];
            foreach( $row as $i => $col ){
                $record[self::HEADERS[$i]] = $col;
            }
            $records[] = $record;
        }
        return $records;
    }

    // TODO - think more about the validation
    private function validateRow( $row ){
        if(count($row) !== count(self::HEADERS)){
            $this->fail_message = "Invalid Column Count";
            return false;
        }
        return true;
    }

    private function validateMatrix($matrix){
        if(count($matrix) === 0){
            $this->fail_message = "There are no rows to upload";
            return false;
        }
        return true;
    }

}