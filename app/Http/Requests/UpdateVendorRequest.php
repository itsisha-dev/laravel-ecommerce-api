<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Vendor;
use Illuminate\Auth\Access\AuthorizationException;

class UpdateVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $vendor = Vendor::where('user_id', $this->user()->id)->first();

        return $this->user()->can('update', $vendor);
    }

    public function rules(): array
    {
        $vendorId = $this->user()->id;

        return [
            'store_name' => 'required|string|unique:vendors,store_name,' . $vendorId . '|max:100',
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
}