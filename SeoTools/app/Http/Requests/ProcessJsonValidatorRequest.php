<?php

namespace App\Http\Requests;

use App\Rules\ValidateDeveloperToolsRule;
use App\Rules\ValidateJsonStringRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcessJsonValidatorRequest extends FormRequest
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
            'content' => ['required', 'string', 'max:20480', new ValidateJsonStringRule(), new ValidateDeveloperToolsRule($this->user())],
        ];
    }
}
