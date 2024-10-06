<?php

namespace App\Http\Requests;

use App\Models\Register;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'device_id' => 'required|exists:devices,id',
            'title' => 'string|required',
            'unit' => 'string|nullable',
            'type' => ['nullable', Rule::in(Register::Types)],
            'input' => 'nullable|in:digital,analog',
            'output' => 'nullable|in:digital,analog',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ]));
    }
}
