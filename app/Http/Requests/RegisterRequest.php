<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;    // Anyone can register
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                            'required',
                            Password::min(8)          // minimum length
                                    ->letters()       // at least one letter
                                    ->mixedCase()     // at least one uppercase & lowercase
                                    ->numbers()       // at least one number
                                    ->symbols(),       // at least one symbol
                                    'confirmed'
                                    // ->uncompromised() // not in known data leaks like “123456” or “password”
                        ],
            'role' => 'required|in:' . implode(',', [
                User::ROLE_ADMIN,
                User::ROLE_VENDOR,
                User::ROLE_CUSTOMER
            ]),
        ];
    }

    /**
     * Show messages if request is invalid.
     */
    public function messages(): array
    {
        return [
            'role.in' => 'Role must be admin, vendor, or customer.',
        ];
    }
}
