<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize()
    {
        // adjust as needed (policy/role)
        return true;
    }

    public function rules()
    {
        return [
            'account_number' => 'required|string|unique:customers,account_number',
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|unique:customers,email',
            'phone'          => 'nullable|string|max:20',
            'password'       => 'required|string|min:6',
            'status'         => 'nullable|in:active,inactive,suspended,pending',
            'plan'           => 'nullable|string|max:100',
        ];
    }
}
