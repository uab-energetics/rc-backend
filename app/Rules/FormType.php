<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FormType extends Enum {

    protected $validTypes = [
        FORM_SIMPLE,
        FORM_EXPERIMENT,
        FORM_WIZARD,
    ];

    /** Create a new rule instance.
     * @return void
     */
    public function __construct() {
        parent::__construct($this->validTypes);
    }
}
