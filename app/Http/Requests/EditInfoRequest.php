<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EditInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'phone_number' => ['regex:/^(((\+|)84)|0)(3|5|7|8|9)+([0-9]{8})\b/'],
            'address' => 'string',
        ];
    }

    public function messages()
    {
        return [
            'phone_number.regex' => 'The phone number must be a valid Vietnamese phone number.'
        ];
    }
}
