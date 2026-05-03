<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Subscription;
use App\Models\Invoice;

class OverduePaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;
    protected $invoice;
    protected $daysOverdue;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription, Invoice $invoice, int $daysOverdue)
    {
        $this->subscription = $subscription;
        $this->invoice = $invoice;
        $this->daysOverdue = $daysOverdue;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $customer = $this->subscription->customer;
        $user = $customer->user;

        return (new MailMessage)
            ->subject("Overdue Payment Alert - {$user->name}")
            ->greeting("Overdue Payment Notification")
            ->line("A payment is {$this->daysOverdue} days overdue.")
            ->line("**Customer:** {$user->name}")
            ->line("**Email:** {$user->email}")
            ->line("**Subscription ID:** {$this->subscription->id}")
            ->line("**Package:** {$this->subscription->package->name}")
            ->line("**Amount Due:** ₱" . number_format($this->invoice->total_amount, 2))
            ->line("**Due Date:** {$this->invoice->due_date->format('M d, Y')}")
            ->action('View Subscription', route('staff.subscriptions.show', $this->subscription->id))
            ->line('Please follow up with the customer regarding this overdue payment.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $customer = $this->subscription->customer;
        $user = $customer->user;

        return [
            'title' => 'Overdue Payment Alert',
            'message' => "{$user->name} has an overdue payment of ₱" . number_format($this->invoice->total_amount, 2) . " ({$this->daysOverdue} days overdue)",
            'type' => 'overdue_payment',
            'subscription_id' => $this->subscription->id,
            'customer_id' => $customer->id,
            'invoice_id' => $this->invoice->id,
            'amount' => $this->invoice->total_amount,
            'days_overdue' => $this->daysOverdue,
            'url' => route('staff.subscriptions.show', $this->subscription->id)
        ];
    }
}
