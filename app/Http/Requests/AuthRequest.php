<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
        return $this->isRegisterRoute() ? $this->registerRules() : $this->loginRules();
    }

    protected function loginRules (): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required'
        ];
    }

    protected function registerRules (): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',];
    }

    protected function isRegisterRoute(): bool
    {
        return $this->routeIs('register.store') || $this->routeIs('register');
    }
}