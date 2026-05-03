<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Process a payment for an invoice
     */
    public function processPayment(Request $request)
    {
        // FIX: Use array for second parameter
        Log::info('=== PAYMENT PROCESS STARTED ===', [
            'request_data' => $request->all(),
            'user_authenticated' => Auth::check(),
            'user_id' => Auth::id()
        ]);

        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('User not authenticated for payment');
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please login first.',
            ], 401);
        }

        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:Gcash,Bank Transfer,Cash,Card',
            'reference_number' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $invoice = Invoice::findOrFail($request->invoice_id);
            $user = Auth::user();

            // FIX: Use array for logging
            Log::info('User attempting payment', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'invoice_id' => $invoice->id
            ]);

            // Check if invoice belongs to the customer
            if ($user->role === 'customer') {
                $customer = $user->customer;
                
                if (!$customer) {
                    Log::error('Customer profile not found for user', ['user_id' => $user->id]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Customer profile not found.',
                    ], 404);
                }

                // Get all invoice IDs for this customer
                $customerInvoiceIds = $customer->subscriptions()
                    ->with('invoices')
                    ->get()
                    ->flatMap(function ($subscription) {
                        return $subscription->invoices->pluck('id');
                    })
                    ->toArray();

                Log::info('Customer invoice IDs', ['invoice_ids' => $customerInvoiceIds]);

                if (!in_array($invoice->id, $customerInvoiceIds)) {
                    Log::warning('Invoice access denied', [
                        'customer_id' => $customer->id,
                        'invoice_id' => $invoice->id
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Invoice not found or access denied.',
                    ], 404);
                }
            }

            // Check if invoice can accept payments
            if ($invoice->is_fully_paid) {
                Log::info('Invoice already paid', ['invoice_id' => $invoice->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'This invoice is already fully paid.',
                ], 422);
            }

            // Check if amount is valid
            $remainingAmount = $invoice->amount_due - $invoice->amount_paid;
            if ($request->amount > $remainingAmount) {
                Log::info('Payment amount exceeds balance', [
                    'amount' => $request->amount,
                    'remaining' => $remainingAmount
                ]);
                return response()->json([
                    'success' => false,
                    'message' => "Payment amount exceeds remaining balance. Maximum payment: " . number_format($remainingAmount, 2),
                ], 422);
            }

            // Create payment record
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount_paid' => $request->amount,
                'method' => $request->payment_method,
                'reference' => $request->reference_number ?: 'MOB-' . time() . '-' . $invoice->id,
                'status' => 'completed', // Auto-complete for demo
                'payment_date' => now(),
                'created_by' => $user->id,
                'payload' => [
                    'processed_by' => 'mobile_app',
                    'customer_id' => $user->customer->id ?? null,
                    'timestamp' => now()->toISOString(),
                    'user_agent' => $request->userAgent(),
                ],
            ]);

            Log::info('Payment created successfully', ['payment_id' => $payment->id]);

            // The invoice status will be automatically updated by the Payment model events
            // No need to call updateStatusFromPayments() manually

            Log::info('Invoice status should be updated automatically via Payment model events');

            DB::commit();

            // Get updated invoice with payments and subscription info
            $updatedInvoice = Invoice::with([
                'payments', 
                'subscription.package',
                'subscription.customer.user'
            ])->find($invoice->id);

            Log::info('=== PAYMENT PROCESS COMPLETED SUCCESSFULLY ===', [
                'invoice_id' => $updatedInvoice->id,
                'status' => $updatedInvoice->status,
                'amount_paid' => $updatedInvoice->amount_paid,
                'amount_due' => $updatedInvoice->amount_due
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully!',
                'payment' => $payment,
                'invoice' => [
                    'id' => $updatedInvoice->id,
                    'invoice_no' => $updatedInvoice->invoice_no,
                    'amount_due' => (float) $updatedInvoice->amount_due,
                    'amount_paid' => (float) $updatedInvoice->amount_paid,
                    'status' => $updatedInvoice->status,
                    'remaining_balance' => (float) $updatedInvoice->amount_due - (float) $updatedInvoice->amount_paid,
                    'subscription' => $updatedInvoice->subscription ? [
                        'id' => $updatedInvoice->subscription->id,
                        'package_name' => $updatedInvoice->subscription->package->name ?? 'Unknown Package',
                    ] : null,
                ],
                'remaining_balance' => $updatedInvoice->remaining_amount,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get payment history for a customer
     */
    public function getPaymentHistory(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.',
            ], 403);
        }

        $customer = $user->customer;
        
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer profile not found.',
            ], 404);
        }

        $payments = Payment::whereHas('invoice', function ($query) use ($customer) {
            $query->whereHas('subscription', function ($q) use ($customer) {
                $q->where('customer_id', $customer->id);
            });
        })
        ->with([
            'invoice' => function ($query) {
                $query->select('id', 'invoice_no', 'amount_due', 'description', 'status', 'subscription_id');
            },
            'invoice.subscription.package'
        ])
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($payment) {
            return [
                'id' => $payment->id,
                'invoice_id' => $payment->invoice_id,
                'invoice_no' => $payment->invoice->invoice_no,
                'amount_paid' => (float) $payment->amount_paid,
                'method' => $payment->method,
                'reference' => $payment->reference,
                'status' => $payment->status,
                'payment_date' => $payment->payment_date,
                'created_at' => $payment->created_at,
                'subscription' => $payment->invoice->subscription ? [
                    'package_name' => $payment->invoice->subscription->package->name ?? 'Unknown Package',
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'payments' => $payments,
        ]);
    }

    /**
     * Test payment endpoint
     */
    public function testPayment(Request $request)
    {
        Log::info('Test payment endpoint called');
        
        return response()->json([
            'success' => true,
            'message' => 'Payment API is working!',
            'user' => Auth::check() ? [
                'id' => Auth::id(),
                'name' => Auth::user()->name,
                'role' => Auth::user()->role
            ] : 'Not authenticated',
            'timestamp' => now()->toISOString(),
        ]);
    }
}