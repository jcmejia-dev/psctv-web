<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('customer') ?? $this->route('id'); // support route model binding or id
        return [
            'account_number' => "required|string|unique:customers,account_number,{$id}",
            'name'           => 'required|string|max:255',
            'email'          => "nullable|email|unique:customers,email,{$id}",
            'phone'          => 'nullable|string|max:20',
            'password'       => 'nullable|string|min:6',
            'status'         => 'nullable|in:active,inactive,suspended,pending',
            'plan'           => 'nullable|string|max:100',
        ];
    }
}
