<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone_number' => ['regex:/^(((\+|)84)|0)(3|5|7|8|9)+([0-9]{8})\b/','nullable'],
            'address' => 'string|nullable',
            'role' => 'required|exists:roles,name|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'phone_number.regex' => 'The phone number must be a valid Vietnamese phone number.'
        ];
    }
}
