<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|confirmed|min:6',];
    }

    protected function isRegisterRoute(): bool
    {
        return $this->routeIs('register.store') || $this->routeIs('register') || $this->routeIs('api.register');
    }
}
