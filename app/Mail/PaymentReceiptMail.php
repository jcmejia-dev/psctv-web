<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $payment;
    public $pdf; // ✅ declare the property

    public function __construct($invoice, $payment, $pdf = null)
    {
        $this->invoice = $invoice;
        $this->payment = $payment;
        $this->pdf = $pdf; // ✅ assign here inside constructor
    }

    public function build()
    {
        $email = $this->subject("Receipt for Invoice #{$this->invoice->invoice_no}")
                      ->view('emails.payment_receipt')
                      ->with([
                          'invoice' => $this->invoice,
                          'payment' => $this->payment,
                      ]);

        if ($this->pdf) {
            $email->attachData($this->pdf->output(), "Receipt-{$this->invoice->invoice_no}.pdf");
        }

        return $email;
    }
}
