<?php

namespace App\Http\Requests;

use App\Rules\ValidateDeveloperToolsRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcessCssMinifierRequest extends FormRequest
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
            'content' => ['required', new ValidateDeveloperToolsRule($this->user())]
        ];
    }
}
