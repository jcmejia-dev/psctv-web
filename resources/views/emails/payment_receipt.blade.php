@component('mail::message')
# 📄 Payment Receipt

Hello **{{ $invoice->subscription->customer->user->name ?? 'Customer' }}**,  
We’re pleased to confirm that we have received your payment.

---

### **🧾 Invoice Details**
**Invoice Number:** {{ $invoice->invoice_no }}  
**Amount Paid:** ₱{{ number_format($payment->amount_paid, 2) }}  
**Payment Method:** {{ $payment->method }}  
**Date Paid:** {{ $payment->payment_date->format('M d, Y h:i A') }}

---

Your official receipt is attached to this email as a PDF file.  
If you have any questions or concerns, feel free to contact us anytime.

Thanks for choosing **Smart Cable Service**!

---

@component('mail::footer')
© {{ date('Y') }} Smart Cable Service — All rights reserved.
@endcomponent
@endcomponent
