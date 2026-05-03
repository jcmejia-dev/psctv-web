<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentDueReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public $daysUntilDue;

    /**
     * Create a new message instance.
     */
    public function __construct(Subscription $subscription, $daysUntilDue = 3)
    {
        $this->subscription = $subscription;
        $this->daysUntilDue = $daysUntilDue;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Due Reminder - ' . $this->daysUntilDue . ' Days Remaining',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_due_reminder',
            with: [
                'subscription' => $this->subscription,
                'daysUntilDue' => $this->daysUntilDue,
                'customer' => $this->subscription->customer,
                'package' => $this->subscription->package,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
