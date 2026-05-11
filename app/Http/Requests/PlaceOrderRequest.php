<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth()->user();
        if (!$user) {
            throw new AuthorizationException('Please login first to place an order.');
        }    
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => ['required', 'min:6', 'max:100', 'regex:/^[a-zA-Z\s\.\-]+$/'],
            'phone' => ['required', 'integer', 'digits:10'],
            'label' => ['required','in:' . implode(',', ['Home','Work','Other'])],
            'address' => 'required',
            'city' => ['required', 'regex:/^[\pL\s\'\-\.]+$/u'],     // \pL-any (Unicode)letter, \s-spaces, apostrophes, hyphens, dots
            'state' => ['required', 'regex:/^[\pL\s\'\-\.]+$/u'],
            'country' => ['min:4', 'regex:/^[\pL\s\'\-\.]+$/u'],
            'postal_code' => ['required', 'integer', 'digits:6'],
        ];
        // return [
        //     'items' => 'required|array|min:1',
        //     'items.*.product_id' => 'required|exists:products,id',
        //     'items.*.quantity' => 'required|integer|min:1'
        // ];
    }

    /**
     * Show messages if request is invalid
     */
    public function messages(): array
    {
        return [
            'fullname.min' => 'Please provide your full name.',
            'fullname.regex' => 'Please provide valid full name.',
            'city.regex' => 'Please provide valid city name.',
            'state.regex' => 'Please provide valid state.',
            'country.regex' => 'Please provide valid country name.',
            'country.min' => 'Please provide valid country name.'
        ];
    }

    /**
     * replace :attribute in error messages with values from attributes()
     */
    // public function attributes(): array
    // {
    //     $attributes = [];

    //     foreach ($this->input('items', []) as $index => $item) {
    //         $position = $index + 1;

    //         $attributes["items.$index.product_id"] = "items - {$position} - product";
    //         $attributes["items.$index.quantity"]   = "items - {$position} - quantity";
    //     }

    //     return $attributes;
    // }
}
