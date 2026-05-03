<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceLog;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class InvoiceLogController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Validate request parameters
            $validated = $request->validate([
                'search' => 'nullable|string|max:255',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date',
                'customer_id' => 'nullable|integer|exists:customers,id',
                'activity_type' => 'nullable|string|in:created,paid,overdue,trial,cancelled',
                'sort_by' => 'nullable|string|in:created_at,message',
                'sort_direction' => 'nullable|string|in:asc,desc',
            ]);

            $query = InvoiceLog::with(['subscription.customer.user', 'invoice']);

            // Search functionality with improved null handling
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('message', 'like', "%{$search}%")
                      ->orWhereHas('subscription.customer.user', function ($userQ) use ($search) {
                          $userQ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('invoice', function ($invoiceQ) use ($search) {
                          $invoiceQ->where('invoice_no', 'like', "%{$search}%");
                      });
                });
            }

            // Date range filter with validation
            if ($request->filled('date_from')) {
                $dateFrom = Carbon::parse($request->date_from)->startOfDay();
                $query->where('created_at', '>=', $dateFrom);
            }

            if ($request->filled('date_to')) {
                $dateTo = Carbon::parse($request->date_to)->endOfDay();
                $query->where('created_at', '<=', $dateTo);
            }

            // Customer filter with null safety
            if ($request->filled('customer_id')) {
                $query->whereHas('subscription.customer', function ($q) use ($request) {
                    $q->where('id', $request->customer_id);
                });
            }

            // Improved Activity type filter with more specific patterns
            if ($request->filled('activity_type')) {
                $activityType = $request->activity_type;
                $patterns = [
                    'created' => ['created', 'generated', 'issued'],
                    'paid' => ['paid', 'payment', 'settled'],
                    'overdue' => ['overdue', 'past due', 'unpaid'],
                    'trial' => ['trial', 'free trial', 'trial period'],
                    'cancelled' => ['cancelled', 'canceled', 'terminated'],
                ];

                if (isset($patterns[$activityType])) {
                    $query->where(function ($q) use ($patterns, $activityType) {
                        foreach ($patterns[$activityType] as $pattern) {
                            $q->orWhere('message', 'like', "%{$pattern}%");
                        }
                    });
                }
            }

            // Sort options with validation
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');
            $query->orderBy($sortBy, $sortDirection);

            $logs = $query->paginate(15)->withQueryString();

            // Get filter options with caching
            $customers = Cache::remember('invoice_log_customers', 300, function () {
                return Customer::with('user')
                    ->whereHas('subscriptions.invoices.logs')
                    ->orderBy('created_at', 'desc')
                    ->get();
            });

            // Optimized stats calculation with caching
            $stats = Cache::remember('invoice_log_stats', 300, function () {
                $now = now();
                return [
                    'total_logs' => InvoiceLog::count(),
                    'this_month' => InvoiceLog::whereYear('created_at', $now->year)
                                             ->whereMonth('created_at', $now->month)
                                             ->count(),
                    'active_customers' => InvoiceLog::select('subscription_id')
                                                   ->distinct()
                                                   ->whereHas('subscription.customer')
                                                   ->count(),
                    'latest_activity' => InvoiceLog::latest('created_at')->value('created_at'),
                ];
            });

            return view('invoices.logs', compact('logs', 'customers', 'stats'));

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Invoice Log Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while loading invoice logs. Please try again.');
        }
    }

    public function showInvoice($id)
    {
        $invoice = Invoice::with(['subscription.customer.user', 'payments'])->findOrFail($id);
        return view('invoices.show_invoice', compact('invoice'));
    }


}
