<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiredSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public $daysExpired;

    public function __construct(Subscription $subscription, $daysExpired)
    {
        $this->subscription = $subscription;
        $this->daysExpired = $daysExpired;
    }

    public function build()
    {
        return $this->subject("Subscription Expired - Action Required")
                    ->view('emails.expired_subscription')
                    ->with([
                        'subscription' => $this->subscription,
                        'daysExpired' => $this->daysExpired,
                        'customer' => $this->subscription->customer ?? null,
                        'package' => $this->subscription->package ?? null,
                    ]);
    }
}
