<?php

namespace App\Http\Requests;

use App\Rules\ValidateContentToolsRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcessNumberGeneratorRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'min' => ['required', 'int', 'min:0', 'max:2147483647', new ValidateContentToolsRule($this->user())],
            'max' => ['required', 'int', 'min:0', 'max:2147483647', 'gt:min']
        ];
    }
}
