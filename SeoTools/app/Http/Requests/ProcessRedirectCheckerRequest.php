<?php

namespace App\Http\Requests;

use App\Rules\ValidateResearchToolsRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcessRedirectCheckerRequest extends FormRequest
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
            'url' => ['required', 'url', 'min:1', 'max:2048', new ValidateResearchToolsRule($this->user())],
        ];
    }
}
