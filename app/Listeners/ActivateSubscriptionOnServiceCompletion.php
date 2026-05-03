<?php

namespace App\Listeners;

use App\Events\ServiceRequestStatusUpdated;
use App\Models\Subscription;
use App\Models\InvoiceLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ActivateSubscriptionOnServiceCompletion
{
    /**
     * Handle the event.
     */
    public function handle(ServiceRequestStatusUpdated $event): void
    {
        $serviceRequest = $event->serviceRequest;

        // Only process if the service request is completed and has a subscription
        if ($serviceRequest->status !== 'completed' || !$serviceRequest->subscription_id) {
            return;
        }

        try {
            $subscription = Subscription::find($serviceRequest->subscription_id);

            if (!$subscription) {
                Log::warning("Subscription not found for completed service request #{$serviceRequest->id}");
                return;
            }

            // Only activate if the subscription is currently pending (from reconnection)
            if ($subscription->status === 'pending' && $subscription->service_status === 'pending') {
                $subscription->update([
                    'status' => 'active',
                    'service_status' => 'active',
                    'activated_at' => now(),
                    'activated_by' => Auth::id(),
                ]);

                // Log the activation
                InvoiceLog::create([
                    'subscription_id' => $subscription->id,
                    'invoice_id'      => null,
                    'user_id'         => Auth::id(),
                    'action'          => 'activated',
                    'message'         => "Subscription activated after service completion. Service request #" . $serviceRequest->id . " completed.",
                    'details'         => json_encode([
                        'service_request_id' => $serviceRequest->id,
                        'activated_at' => now()->toDateTimeString(),
                    ]),
                ]);

                Log::info("✅ Subscription #{$subscription->id} activated after service request #{$serviceRequest->id} completion");
            }
        } catch (\Throwable $e) {
            Log::error("❌ Failed to activate subscription after service completion: " . $e->getMessage(), [
                'service_request_id' => $serviceRequest->id,
                'subscription_id' => $serviceRequest->subscription_id,
            ]);
        }
    }
}
