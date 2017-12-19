<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Question implements Rule {

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        $this->validator = static::questionValidator($value);
        return !$this->validator->fails();
    }

    /** @var \Illuminate\Contracts\Validation\Validator */
    private $validator = null;

    public static function questionValidator($data) {
        return Validator::make($data, [
            'name' => 'required|string',
            'prompt' => 'required|string',
            'default_format' => ['required', new ResponseType()],
            'options.*.txt' => 'distinct',
            'accepts.*.type' => ['distinct', new ResponseType()],
        ]);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        if ($this->validator === null) {
            return 'Invalid Question';
        }
        return $this->validator->getMessageBag()->first();
    }
}
