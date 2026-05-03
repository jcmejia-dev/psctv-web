<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bill;
    public $payment;
    public $notes;
    public $pdf;

    public function __construct($bill, $payment, $notes, $pdf)
    {
        $this->bill = $bill;
        $this->payment = $payment;
        $this->notes = $notes;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Your Official Payment Receipt')
            ->view('emails.receipt_email')  // email body
            ->attachData($this->pdf, 'receipt.pdf', [
                'mime' => 'application/pdf',
            ])
            ->with([
                'bill' => $this->bill,
                'payment' => $this->payment,
                'notes' => $this->notes,
            ]);
    }
}
