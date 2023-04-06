<?php

namespace App\Http\Requests;

use App\Rules\ValidateContentToolsRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcessBinaryConverterRequest extends FormRequest
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
            'content' => ['required', 'string', 'min:1', 'max:10240', new ValidateContentToolsRule($this->user())],
            'type' => ['required', 'string', 'in:binary,text']
        ];
    }
}
