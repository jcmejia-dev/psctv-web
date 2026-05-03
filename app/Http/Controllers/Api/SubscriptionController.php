<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Customer;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Fetch all subscriptions for a given customer.
     * GET /api/customers/{id}/subscriptions
     */
    public function index($id)
    {
        $customer = Customer::with([
            'subscriptions.package',
            'subscriptions.invoices' => function($query) {
                $query->whereIn('status', ['unpaid', 'partial', 'overdue']);
            }
        ])->find($id);

        if (!$customer) {
            return response()->json([
                'status'  => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        $subscriptions = $customer->subscriptions()
            ->with([
                'package:id,name,price,billing_cycle',
                'invoices' => function($query) {
                    $query->select('id', 'subscription_id', 'invoice_no', 'description', 'amount_due', 'amount_paid', 'status', 'due_date', 'billing_date')
                          ->whereIn('status', ['unpaid', 'partial', 'overdue']);
                }
            ])
            ->latest('start_date')
            ->get()
            ->map(function ($subscription) {
                return [
                    'id' => $subscription->id,
                    'package_id' => $subscription->package_id,
                    'package_name' => $subscription->package->name ?? 'Unknown Package',
                    'price' => (float) $subscription->package->price ?? 0,
                    'billing_cycle' => $subscription->package->billing_cycle ?? 'monthly',
                    'status' => $subscription->status,
                    'start_date' => $subscription->start_date,
                    'end_date' => $subscription->end_date,
                    'next_billing_date' => $subscription->next_billing_date,
                    'pending_invoices' => $subscription->invoices->map(function ($invoice) {
                        return [
                            'id' => $invoice->id,
                            'invoice_no' => $invoice->invoice_no,
                            'description' => $invoice->description,
                            'amount_due' => (float) $invoice->amount_due,
                            'amount_paid' => (float) $invoice->amount_paid,
                            'status' => $invoice->status,
                            'due_date' => $invoice->due_date,
                            'billing_date' => $invoice->billing_date,
                        ];
                    }),
                ];
            });

        return response()->json([
            'status'        => true,
            'customer_id'   => $customer->id,
            'customer_name' => $customer->user->name ?? null,
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Fetch a single active subscription for a customer.
     * GET /api/customers/{id}/subscription
     */
    public function show($id)
    {
        $subscription = Subscription::with(['package', 'invoices' => function($query) {
            $query->whereIn('status', ['unpaid', 'partial', 'overdue']);
        }])
            ->where('customer_id', $id)
            ->where('status', 'active')
            ->latest('start_date')
            ->first();

        if (!$subscription) {
            return response()->json([
                'status'  => false,
                'message' => 'No active subscription found for this customer.',
            ]);
        }

        $formattedSubscription = [
            'id' => $subscription->id,
            'package_name' => $subscription->package->name ?? 'Unknown Package',
            'price' => (float) $subscription->package->price ?? 0,
            'billing_cycle' => $subscription->package->billing_cycle ?? 'monthly',
            'status' => $subscription->status,
            'start_date' => $subscription->start_date,
            'end_date' => $subscription->end_date,
            'next_billing_date' => $subscription->next_billing_date,
            'pending_invoices' => $subscription->invoices->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'amount_due' => (float) $invoice->amount_due,
                    'amount_paid' => (float) $invoice->amount_paid,
                    'status' => $invoice->status,
                    'due_date' => $invoice->due_date,
                    'billing_date' => $invoice->billing_date,
                ];
            }),
        ];

        return response()->json([
            'status'       => true,
            'subscription' => $formattedSubscription,
        ]);
    }

    /**
     * Get subscription invoices for payment
     * GET /api/subscriptions/{id}/invoices
     */
    public function getInvoices($id)
    {
        $subscription = Subscription::with(['invoices' => function($query) {
            $query->whereIn('status', ['unpaid', 'partial', 'overdue']);
        }])
            ->where('id', $id)
            ->first();

        if (!$subscription) {
            return response()->json([
                'status'  => false,
                'message' => 'Subscription not found.',
            ], 404);
        }

        $invoices = $subscription->invoices()
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'description' => $invoice->description,
                    'amount_due' => (float) $invoice->amount_due,
                    'amount_paid' => (float) $invoice->amount_paid,
                    'status' => $invoice->status,
                    'due_date' => $invoice->due_date,
                    'billing_date' => $invoice->billing_date,
                    'remaining_balance' => (float) $invoice->amount_due - (float) $invoice->amount_paid,
                ];
            });

        return response()->json([
            'status' => true,
            'subscription' => [
                'id' => $subscription->id,
                'package_name' => $subscription->package->name ?? 'Unknown Package',
            ],
            'invoices' => $invoices,
        ]);
    }
}