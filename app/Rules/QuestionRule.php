<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class QuestionRule implements Rule {

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value) {
        $this->validator = static::newQuestionValidator($value);
        return !$this->validator->fails();
    }

    /** @var \Illuminate\Contracts\Validation\Validator */
    private $validator = null;

    public static function newQuestionValidator($data) {
        return Validator::make($data, [
            'name' => 'required|string',
            'prompt' => 'required|string',
            'default_format' => ['required', 'in_array:accepts.*.type', new ResponseType()],
            'options.*.txt' => 'required|distinct',
            'accepts.*.type' => ['required', 'distinct', new ResponseType()],
        ]);
    }

    public static function existingQuestionValidator($data) {
        return Validator::make($data, [
            'name' => 'string',
            'prompt' => 'string',
            'default_format' =>  [new ResponseType(), 'in_array:accepts.*.type'],
            'options.*.txt' => 'required|distinct',
            'accepts.*.type' => ['required', 'distinct', new ResponseType()],
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
