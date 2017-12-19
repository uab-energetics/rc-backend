<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Enum implements Rule {

    protected $validValues;

    /** Create a new rule instance.
     * @param array $validValues
     */
    public function __construct(array $validValues = []) {
        $this->validValues = $validValues;
    }

    /** Determine if the validation rule passes.
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        return in_array($value, $this->validValues);
    }

    /** Get the validation error message.
     * @return string
     */
    public function message() {
        return 'The :attribute must be one of ' . join("|", $this->validValues);
    }
}
