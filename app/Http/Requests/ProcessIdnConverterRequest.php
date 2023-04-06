<?php

namespace App\Http\Requests;

use App\Rules\ValidateDomainNameRule;
use App\Rules\ValidateResearchToolsRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcessIdnConverterRequest extends FormRequest
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
            'domain' => ['required', 'string', 'max:2048', new ValidateDomainNameRule(), new ValidateResearchToolsRule($this->user())],
            'type' => ['required', 'string', 'in:punycode,text']
        ];
    }
}
