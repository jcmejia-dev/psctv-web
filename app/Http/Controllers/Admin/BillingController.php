<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Package;
use App\Models\Customer;
use App\Models\ServiceRequest;
use App\Mail\PaymentReceiptMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Services\InvoiceService;
use App\Services\PaymentService;

class BillingController extends Controller
{
    protected $invoiceService;
    protected $paymentService;

    public function __construct(InvoiceService $invoiceService, PaymentService $paymentService)
    {
        $this->invoiceService = $invoiceService;
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of bills with filters, sorting, pagination, and summary.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['subscription.customer.user']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('subscription.customer.user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
        }

        if ($request->filled('from')) {
            $query->whereDate('due_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('due_date', '<=', $request->to);
        }

        // Sorting
        $allowedSorts = ['id', 'invoice_no', 'amount_due', 'due_date', 'status', 'created_at'];
        $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'id';
        $order = $request->get('order', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $order);

        $bills = $query->paginate(10)->appends($request->query());

        // ✅ FIXED SUMMARY LOGIC
        $summary = [
            'total_collected' => Invoice::where('status', 'paid')->sum('amount_due'),

            // Pending = unpaid but not yet overdue
            'pending' => Invoice::where('status', 'unpaid')
                ->whereDate('due_date', '>=', now())
                ->sum('amount_due'),

            // Overdue = count of users + total amount
            'overdue' => [
                'amount' => Invoice::where(function ($q) {
                    $q->where('status', 'overdue')
                      ->orWhere(function ($sub) {
                          $sub->where('status', 'unpaid')
                              ->whereDate('due_date', '<', now());
                      });
                })->sum('amount_due'),

                'customers' => Invoice::where(function ($q) {
                    $q->where('status', 'overdue')
                      ->orWhere(function ($sub) {
                          $sub->where('status', 'unpaid')
                              ->whereDate('due_date', '<', now());
                      });
                })->distinct('customer_id')->count('customer_id'),
            ],
        ];

        return view('billing.index', compact('bills', 'summary'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $subscriptions = Subscription::with(['customer.user', 'package'])->get();
        $customers = Customer::with('user')->get();
        $plans = Package::where('is_active', true)->orderBy('price')->get();

        return view('billing.create', compact('subscriptions', 'customers', 'plans'));
    }

    /**
     * Store a newly created bill.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'customer_name'   => 'nullable|string|max:255',
            'reason'          => 'nullable|string|max:255',
            'amount'          => 'required|numeric|min:0',
            'due_date'        => 'required|date',
            'notes'           => 'nullable|string|max:500',
        ]);

        $subscription = null; // Initialize to avoid undefined variable error
        $description = $validated['reason'] ?? null;

        // If linked to subscription, use package name as description
        if ($validated['subscription_id']) {
            $subscription = Subscription::with('package')->find($validated['subscription_id']);
            $description = $subscription?->package?->name ?? 'Subscription Bill';
        }

        // If no subscription and no reason, fallback
        if (!$validated['subscription_id'] && !$description) {
            $description = 'Manual Bill';
        }

        // Invoice number handled automatically by model boot()
        $invoice = Invoice::create([
            'subscription_id' => $validated['subscription_id'] ?? null,
            'customer_id'     => $subscription?->customer_id ?? null,
            'customer_name'   => $validated['customer_name'] ?? null,
            'description'     => $description,
            'amount_due'      => $validated['amount'],
            'due_date'        => $validated['due_date'],
            'billing_date'    => now(),
            'status'          => 'unpaid',
            'notes'           => $validated['notes'] ?? null,
        ]);

        return redirect()->route('billing.show', $invoice->id)
            ->with('success', "Bill #{$invoice->invoice_no} created successfully.");
    }

    /**
     * Display a single bill.
     */
    public function show($id)
    {
        $bill = Invoice::with(['subscription.customer.user', 'payments'])->findOrFail($id);
        return view('billing.show', compact('bill'));
    }

    /**
     * Mark a bill as paid.
     */
    public function markPaid(Request $request, $id)
{
    $invoice = Invoice::findOrFail($id);

    if (strtolower($invoice->getRawOriginal('status')) === 'paid') {
        return back()->with('info', "Bill #{$invoice->invoice_no} is already marked as paid.");
    }

    $validated = $request->validate([
        'amount'            => 'nullable|numeric|min:0',
        'amount_received'   => 'nullable|numeric|min:0',
        'method'            => 'nullable|string|max:50',
        'reference'         => 'nullable|string|max:100',
        'notes'             => 'nullable|string|max:500',
    ]);

    $amount = $validated['amount'] ?? $invoice->amount_due;
    $amountReceived = $validated['amount_received'] ?? $amount;
    $change = $amountReceived - $amount;

    // Validate that amount received is sufficient
    if ($amountReceived < $amount && $validated['method'] === 'Cash') {
        return back()->with('error', "Insufficient payment! Amount received (₱" . number_format($amountReceived, 2) . ") is less than amount due (₱" . number_format($amount, 2) . ")");
    }

    // Create Payment Record with change info
    $payment = Payment::create([
        'invoice_id'   => $invoice->id,
        'amount_paid'  => $amount,
        'payment_date' => now(),
        'method'       => $validated['method'] ?? 'Cash',
        'reference'    => $validated['reference'] ?? null,
        'status'       => 'paid',
        'payload'      => [
            'notes' => $validated['notes'] ?? null,
            'amount_received' => $amountReceived,
            'change' => $change > 0 ? $change : 0,
        ],
        'created_by'   => Auth::id(),
    ]);

    // Update Invoice
    $invoice->update([
        'amount_paid' => $amount,
        'status'      => 'paid',
    ]);

    // Activate subscription if pending
    if ($invoice->subscription && $invoice->subscription->status === 'pending') {
        $invoice->subscription->update(['status' => 'active']);
    }

    // Reactivate subscription if it was suspended or disconnected before payment
    if ($invoice->subscription &&
        in_array($invoice->subscription->service_status, ['suspended', 'disconnected'])) {
        $oldServiceStatus = $invoice->subscription->service_status;
        $invoice->subscription->update([
            'service_status' => 'active',
            'reconnected_at' => now(),
            'reconnected_by' => Auth::id(),
        ]);

        // Log the reactivation
        \App\Models\InvoiceLog::create([
            'subscription_id' => $invoice->subscription->id,
            'invoice_id'      => $invoice->id,
            'user_id'         => Auth::id(),
            'action'          => 'reactivated',
            'message'         => "Subscription reactivated due to payment of invoice #{$invoice->invoice_no}",
            'details'         => json_encode([
                'old_service_status' => $oldServiceStatus,
                'new_service_status' => 'active',
                'invoice_number' => $invoice->invoice_no,
                'reactivated_at' => now()->toDateTimeString(),
            ]),
        ]);
    }

    /* ==========================================================
     |   SEND RECEIPT EMAIL TO CUSTOMER
     ========================================================== */
    if (
        $invoice->subscription &&
        $invoice->subscription->customer &&
        $invoice->subscription->customer->user
    ) {
        $customerEmail = $invoice->subscription->customer->user->email;

        if ($customerEmail) {
            Log::info("📧 Attempting receipt email to: {$customerEmail}");
            $maxRetries = 3;

            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    Mail::to($customerEmail)->send(
                        new PaymentReceiptMail($invoice, $payment)
                    );

                    if (count(Mail::failures()) > 0) {
                        throw new \Exception("Mail::failures() returned error.");
                    }

                    Log::info("✅ Receipt email sent to {$customerEmail}");
                    break; // EXIT retry loop on success
                } catch (\Throwable $e) {
                    Log::warning("⚠️ Attempt {$attempt}/{$maxRetries} failed: " . $e->getMessage());

                    if ($attempt == $maxRetries) {
                        Log::error("❌ All retry attempts failed. Giving up.");
                        return redirect()->route('billing.show', $invoice->id)
                            ->with('warning', "Bill #{$invoice->invoice_no} marked as paid, but the receipt email FAILED to send.");
                    }
                    sleep(2); // wait before retry
                }
            }
        } else {
            Log::warning("⚠️ Customer has no email address. ID: " . $invoice->subscription->customer->id);
        }
    }

    $successMessage = "Bill #{$invoice->invoice_no} marked as paid and receipt sent.";
    
    // Add change info to success message if applicable
    if ($change > 0) {
        $successMessage .= " Change to return: ₱" . number_format($change, 2);
    }

    return redirect()->route('billing.show', $invoice->id)
        ->with('success', $successMessage);
}
    /**
     * Delete or cancel a bill.
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoiceNo = $invoice->invoice_no;

        // Check if invoice is already paid
        if ($invoice->status === 'paid') {
            return redirect()->route('billing.index')
                ->with('error', "Cannot delete paid bill #{$invoiceNo}.");
        }

        // Check if invoice is recurring (subscription-based)
        if ($invoice->is_recurring) {
            return redirect()->route('billing.index')
                ->with('error', "Cannot delete recurring bill #{$invoiceNo}. Cancel the subscription instead.");
        }

        $this->invoiceService->cancelInvoice($invoice);

        return redirect()->route('billing.index')
            ->with('success', "Bill #{$invoiceNo} cancelled successfully.");
    }

    /**
     * View receipt (web).
     */
    public function receipt(Request $request, $id)
    {
        $bill = Invoice::with(['subscription.customer.user', 'payments.user'])->findOrFail($id);
        $payment = $bill->payments->last();
        $notes = $request->query('notes') ?? 'Thank you for your payment.';

        return view('billing.receipt', compact('bill', 'payment', 'notes'));
    }

    /**
     * Download receipt as PDF.
     */
    public function downloadReceipt($id)
    {
        $bill = Invoice::with(['subscription.customer.user', 'payments.user'])->findOrFail($id);
        $payment = $bill->payments->last();
        $notes = 'Thank you for your payment.';

        $pdf = Pdf::loadView('billing.receipt_pdf', compact('bill', 'payment', 'notes'));
        return $pdf->download("Receipt-{$bill->invoice_no}.pdf");
    }


}
