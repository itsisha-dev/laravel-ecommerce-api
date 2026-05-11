<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Vendor;
use Illuminate\Auth\Access\AuthorizationException;

class SearchVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // public function rules(): array
    // {
    //     //
    // }

    /**
     * Show messages if request is invalid.
     */
    // public function messages(): array
    // {
    //     //
    // }

    /**
     * Validate vendor profile already exists
     */
    public function withValidator($validator)
    {
        //
    }
}