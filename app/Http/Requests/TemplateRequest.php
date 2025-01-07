<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable',
            'rows' => 'required|numeric|min:1|max:3',
            'columns' => 'required|numeric|min:1|max:3',
            'devices.*' => 'nullable|exists:devices,id',
            'registers.*' => 'nullable|exists:registers,id',
        ];
    }
}
