<?php

namespace App\Http\Requests;

use App\Rules\ValidateContentToolsRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcessTextReplacerRequest extends FormRequest
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
            'content' => ['required', 'string', 'max:20480', new ValidateContentToolsRule($this->user())],
            'find' => ['required', 'string', 'max:256'],
            'replace' => ['required', 'string', 'max:256']
        ];
    }
}
