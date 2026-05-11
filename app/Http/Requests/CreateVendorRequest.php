<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Vendor;

class CreateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Vendor::class);
    }

    public function rules(): array
    {
        return [
            'user_id' => 'exists:users,id',
            'store_name' => 'required|string|unique:vendors,store_name|max:100',
            'phone' => 'required|digits:10',
            'address' => 'nullable|string',
            'status' => 'in:' . implode(',', [
                Vendor::STATUS_PENDING,
                Vendor::STATUS_APPROVED,
                Vendor::STATUS_SUSPENDED,
                Vendor::STATUS_REJECTED
            ]),
        ];
    }

    /**
     * Show messages if request is invalid.
     */
    public function messages(): array
    {
        return [
            'status.in' => 'Status must be pending, approved, suspended, or rejected.',
        ];
    }

    /**
     * Validate vendor profile already exists
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (Vendor::where('user_id', $this->user()->id())->exists()) {
                $validator->errors()->add('user_id', 'Vendor profile already exists.');
            }
        });
    }
}