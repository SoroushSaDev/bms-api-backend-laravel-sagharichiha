<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest as FRequest;

class FormRequest extends FRequest
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
            'content' => [(request()->method() == 'post' ? 'nullable' : 'required'), 'string'],
            'name' => [(request()->method() == 'post' ? 'required' : 'nullable'), 'string'],
            'objects' => request()->method() == 'post' ? 'required' : 'nullable',
        ];
    }
}
