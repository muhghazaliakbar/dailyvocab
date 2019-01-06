<?php

namespace App\Http\Requests;

use App\Rules\VocabularyRule;
use App\Vocabulary;
use Illuminate\Foundation\Http\FormRequest;

class CreateVocabularyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => [
                'required',
                'unique:vocabularies,text',
                'alpha',
                new VocabularyRule,
            ]
        ];
    }
}
