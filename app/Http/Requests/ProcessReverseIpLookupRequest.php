<?php

namespace App\Http\Requests;

use App\Rules\ValidateResearchToolsRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcessReverseIpLookupRequest extends FormRequest
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
            'ip' => ['required', 'string', 'max:64', new ValidateResearchToolsRule($this->user())]
        ];
    }
}
