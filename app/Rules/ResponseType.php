<?php

namespace App\Rules;

class ResponseType extends Enum {
    protected $validTypes = [
        RESPONSE_TEXT,
        RESPONSE_RANGE,
        RESPONSE_NUMBER,
        RESPONSE_BOOL,
        RESPONSE_SELECT,
        RESPONSE_MULTI_SELECT,
        RESPONSE_NOT_REPORTED,
    ];

    /** Create a new rule instance.
     * @return void
     */
    public function __construct() {
        parent::__construct($this->validTypes);
    }
}
