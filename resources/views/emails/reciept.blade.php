@component('mail::message')
# 🧾 Payment Receipt

Hello **{{ $invoice->subscription->customer->user->name ?? 'Customer' }}**,

We’ve received your payment for:

**Invoice #:** {{ $invoice->invoice_no }}  
**Amount Paid:** ₱{{ number_format($payment->amount, 2) }}  
**Method:** {{ $payment->method }}  
**Date:** {{ $payment->payment_date->format('M d, Y h:i A') }}

Your official receipt is attached as a PDF.

Thanks for trusting **Smart Cable Service**.

@component('mail::footer')
© {{ date('Y') }} Smart Cable Service. All rights reserved.
@endcomponent
@endcomponent
