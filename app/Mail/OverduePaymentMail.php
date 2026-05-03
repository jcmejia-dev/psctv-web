<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OverduePaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $daysOverdue;

    public function __construct(Invoice $invoice, $daysOverdue)
    {
        $this->invoice = $invoice;
        $this->daysOverdue = $daysOverdue;
    }

    public function build()
    {
        return $this->subject("Overdue Payment Notice - Invoice #{$this->invoice->invoice_no}")
                    ->view('emails.overdue_payment')
                    ->with([
                        'invoice' => $this->invoice,
                        'daysOverdue' => $this->daysOverdue,
                        'customer' => $this->invoice->subscription->customer ?? null,
                    ]);
    }
}
