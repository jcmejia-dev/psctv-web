<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true; // add role check later
    }

    public function rules()
    {
        return [
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'reason'          => 'required|string|max:255',
            'amount'          => 'required|numeric|min:0',
            'due_date'        => 'required|date',
            'notes'           => 'nullable|string|max:500',
        ];
    }
}

