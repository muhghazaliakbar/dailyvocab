<?php

namespace App\Rules;

use App\Vocabulary;
use Illuminate\Contracts\Validation\Rule;

class VocabularyRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $lastCharacter = mb_substr( Vocabulary::latest()->first()->text, -1);

        return strtolower($value[0]) === strtolower($lastCharacter);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The first character must be same with last of the latest word in vocabularies data.';
    }
}
