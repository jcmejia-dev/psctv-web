<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $formData;
    public $isConfirmation;

    /**
     * Create a new message instance.
     */
    public function __construct($formData, $isConfirmation = false)
    {
        $this->formData = $formData;
        $this->isConfirmation = $isConfirmation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->isConfirmation) {
            return new Envelope(
                subject: 'Thank you for contacting PSCTV',
            );
        }

        return new Envelope(
            subject: 'New Contact Form Submission - ' . ucfirst($this->formData['service']) . ' Inquiry',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->isConfirmation) {
            return new Content(
                view: 'emails.contact-confirmation',
                with: [
                    'name' => $this->formData['name'],
                    'service' => ucfirst($this->formData['service']),
                ]
            );
        }

        return new Content(
            view: 'emails.contact-form',
            with: [
                'name' => $this->formData['name'],
                'email' => $this->formData['email'],
                'phone' => $this->formData['phone'] ?? 'Not provided',
                'service' => ucfirst($this->formData['service']),
                'message' => $this->formData['message'],
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
