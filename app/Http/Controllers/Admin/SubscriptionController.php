<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\InvoiceLog;
use App\Models\PlanChangeRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['customer.user', 'package']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($mainQ) use ($q) {
                $mainQ->whereHas('customer.user', function ($userQ) use ($q) {
                    $userQ->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                })->orWhereHas('package', function ($pkgQ) use ($q) {
                    $pkgQ->where('name', 'like', "%{$q}%");
                });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                // Active means both status and service_status are 'active'
                $query->where('status', 'active')->where('service_status', 'active');
            } elseif ($request->status === 'suspended') {
                // Suspended means status is active but service_status is suspended
                $query->where('status', 'active')->where('service_status', 'suspended');
            } elseif ($request->status === 'disconnected') {
                // Disconnected means service_status is disconnected
                $query->where('service_status', 'disconnected');
            } else {
                $query->where('status', $request->status);
            }
        }

        // Get subscriptions with pagination
        $subscriptions = $query->latest()->paginate(10);

        // Calculate stats - properly count active subscriptions (both status and service_status active)
        $stats = [
            'total' => $subscriptions->total(),
            'active' => Subscription::where('status', 'active')->where('service_status', 'active')->count(),
            'suspended' => Subscription::where('status', 'active')->where('service_status', 'suspended')->count(),
            'disconnected' => Subscription::where('service_status', 'disconnected')->count(),
            'pending' => Subscription::where('status', 'pending')->count(),
            'cancelled' => Subscription::where('status', 'cancelled')->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
        ];

        return view('subscriptions.index', compact('subscriptions', 'stats'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        $packages  = Package::where('is_active', true)->get();

        return view('subscriptions.create', compact('customers', 'packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id'  => 'required|exists:packages,id',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'status'      => ['nullable', Rule::in(['active', 'pending', 'inactive', 'cancelled', 'expired'])],
        ]);

        DB::beginTransaction();

        try {
            $customer = Customer::findOrFail($validated['customer_id']);
            $package  = Package::findOrFail($validated['package_id']);
            $start    = Carbon::parse($validated['start_date']);

            // Auto-calculate end_date if not given
            $end = !empty($validated['end_date'])
                ? Carbon::parse($validated['end_date'])
                : match (strtolower($package->billing_cycle ?? 'monthly')) {
                    'yearly'    => $start->copy()->addYear(),
                    'quarterly' => $start->copy()->addMonths(3),
                    'weekly'    => $start->copy()->addWeek(),
                    default     => $start->copy()->addMonth(),
                };

            // Determine next billing date
            $nextBilling = match (strtolower($package->billing_cycle ?? 'monthly')) {
                'yearly'    => $start->copy()->addYear(),
                'quarterly' => $start->copy()->addMonths(3),
                'weekly'    => $start->copy()->addWeek(),
                default     => $start->copy()->addMonth(),
            };

            // Set trial period if package has free trial days
            $trialEndDate = null;
            $serviceStatus = 'active';
            if ($package->free_trial_days > 0) {
                $trialEndDate = $start->copy()->addDays($package->free_trial_days);
                $serviceStatus = 'active'; // Service is active during trial
            }

            // Create subscription with user_id from customer
            $subscription = Subscription::create([
                'customer_id'       => $customer->id,
                'user_id'           => $customer->user_id, // Link to user automatically
                'package_id'        => $validated['package_id'],
                'start_date'        => $start->toDateString(),
                'end_date'          => $end->toDateString(),
                'status'            => $validated['status'] ?? 'active',
                'next_billing_date' => $trialEndDate ? $trialEndDate->toDateString() : $nextBilling->toDateString(),
                'service_status'    => $serviceStatus,
                'trial_end_date'    => $trialEndDate?->toDateString(),
                'pending_package_id' => null, // No pending changes initially
            ]);

            // Only create invoice if not in trial period
            if (!$subscription->is_in_trial) {
                $dueDate = $end->copy();

                $invoice = $subscription->invoices()->create([
                    'subscription_id'  => $subscription->id,
                    'customer_id'      => $customer->id,
                    'customer_name'    => $customer->user->name ?? null,
                    'customer_contact' => $customer->user->email ?? null,
                    'invoice_no'       => 'INV-' . str_pad((Invoice::max('id') + 1), 6, '0', STR_PAD_LEFT),
                    'period_start'     => $start->toDateString(),
                    'period_end'       => $end->toDateString(),
                    'amount_due'       => $package->price ?? 0,
                    'amount_paid'      => 0,
                    'due_date'         => $dueDate->toDateString(),
                    'billing_date'     => $start->toDateString(),
                    'is_recurring'     => true,
                    'notes'            => "Subscription plan: {$package->name}",
                    'status'           => 'unpaid',
                    'description'      => $package->name,
                ]);

                // Log invoice creation
                InvoiceLog::create([
                    'subscription_id' => $subscription->id,
                    'invoice_id'      => $invoice->id,
                    'message'         => "Invoice #{$invoice->invoice_no} created for {$package->name}",
                ]);
            } else {
                // Log trial start
                InvoiceLog::create([
                    'subscription_id' => $subscription->id,
                    'invoice_id'      => null,
                    'message'         => "Free trial started for {$package->name} (ends {$trialEndDate->toDateString()})",
                ]);
            }

            DB::commit();

            return redirect()
                ->route('subscriptions.show', $subscription->id)
                ->with('success', 'Subscription created successfully with initial invoice.');

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Subscription store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create subscription: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $subscription = Subscription::with([
            'customer.user', 
            'package', 
            'invoices.payments',
            'pendingPackage', // Relationship for pending package changes
            'planChangeRequests' => function($query) {
                $query->latest()->limit(5);
            }
        ])->findOrFail($id);
        
        return view('subscriptions.show', compact('subscription'));
    }

    public function edit($id)
    {
        $subscription = Subscription::with(['customer.user', 'package', 'pendingPackage'])->findOrFail($id);
        $customers = Customer::with('user')->get();
        $packages  = Package::where('is_active', true)->get();

        return view('subscriptions.edit', compact('subscription', 'customers', 'packages'));
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::with(['customer.user', 'package', 'invoices'])->findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id'  => 'required|exists:packages,id',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'status'      => ['nullable', Rule::in(['active','pending','inactive','cancelled','expired'])],
            'service_status' => ['nullable', Rule::in(['active', 'suspended', 'disconnected'])],
            'change_type' => ['nullable', Rule::in(['update', 'reconnect_current', 'reconnect_new', 'plan_change'])],
            'effective_option' => ['nullable', Rule::in(['next_cycle', 'immediate'])],
        ]);

        DB::beginTransaction();

        try {
            $newPackage = Package::findOrFail($validated['package_id']);
            $start = Carbon::parse($validated['start_date']);
            $now = Carbon::now();
            $changeType = $request->input('change_type', 'update');

            // Determine if this is a package change
            $isPackageChange = $validated['package_id'] != $subscription->package_id;
            
            // Handle different scenarios
            if ($isPackageChange) {
                return $this->handlePlanChange($subscription, $validated, $newPackage, $start, $changeType, $request);
            } else {
                return $this->handleRegularUpdate($subscription, $validated, $newPackage, $start, $changeType);
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Subscription update error: ' . $e->getMessage(), [
                'subscription_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update subscription: ' . $e->getMessage()]);
        }
    }

    /**
     * Handle regular subscription updates (non-package changes)
     */
    private function handleRegularUpdate($subscription, $validated, $newPackage, $start, $changeType)
    {
        try {
            $now = Carbon::now();
            
            // Auto-calculate end_date if not provided
            $end = !empty($validated['end_date'])
                ? Carbon::parse($validated['end_date'])
                : $this->calculateEndDate($start, $newPackage->billing_cycle);

            // Calculate next billing date based on current state
            $nextBillingDate = $this->calculateNextBillingDate($subscription, $start, $end, $newPackage, false);

            // Handle trial period logic
            $trialEndDate = $this->handleTrialPeriod($subscription, $newPackage, $start, false);

            // Determine service status
            $serviceStatus = $this->determineServiceStatus(
                $subscription, 
                $validated['status'] ?? $subscription->status, 
                $trialEndDate,
                $validated['service_status'] ?? $subscription->service_status,
                $changeType
            );

            // Update subscription
            $subscription->update([
                'customer_id'       => $validated['customer_id'],
                'package_id'        => $validated['package_id'],
                'start_date'        => $start->toDateString(),
                'end_date'          => $end->toDateString(),
                'status'            => $validated['status'] ?? $subscription->status,
                'next_billing_date' => $nextBillingDate->toDateString(),
                'service_status'    => $serviceStatus,
                'trial_end_date'    => $trialEndDate?->toDateString(),
                'updated_by'        => Auth::id(),
                'updated_at'        => $now,
            ]);

            // Handle reconnection if needed
            if ($changeType === 'reconnect_current' && $subscription->service_status === 'disconnected') {
                $this->handleReconnection($subscription, 'current');
            }

            // Log the update
            InvoiceLog::create([
                'subscription_id' => $subscription->id,
                'user_id'         => Auth::id(),
                'action'          => 'updated',
                'message'         => "Subscription updated by " . (Auth::user()->name ?? 'Admin'),
                'details'         => json_encode([
                    'changes' => [
                        'customer_changed' => $validated['customer_id'] != $subscription->customer_id,
                        'start_date_changed' => $validated['start_date'] !== $subscription->start_date,
                        'end_date_changed' => ($validated['end_date'] ?? $subscription->end_date) !== $subscription->end_date,
                        'status_changed' => ($validated['status'] ?? $subscription->status) !== $subscription->status,
                        'service_status_changed' => $serviceStatus !== $subscription->service_status,
                    ],
                    'old_package' => $subscription->package->name,
                    'new_end_date' => $end->toDateString(),
                    'new_next_billing' => $nextBillingDate->toDateString(),
                ]),
            ]);

            DB::commit();

            return redirect()->route('subscriptions.show', $subscription->id)
                ->with('success', 'Subscription updated successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Handle plan change requests (PLDT/Globe style)
     */
    private function handlePlanChange($subscription, $validated, $newPackage, $start, $changeType, $request)
    {
        try {
            $now = Carbon::now();
            $effectiveOption = $request->input('effective_option', 'next_cycle');
            $currentPackage = $subscription->package;
            
            // Check if there are unpaid invoices for the current plan
            $hasUnpaidInvoices = $subscription->invoices()
                ->where('status', 'unpaid')
                ->exists();
            
            // Check if there are overdue invoices
            $hasOverdueInvoices = $subscription->invoices()
                ->where('status', 'overdue')
                ->exists();
            
            // PLDT/Globe Logic: Plan changes have different rules
            if ($hasOverdueInvoices) {
                // If overdue, customer must pay first before any plan change
                return back()
                    ->withInput()
                    ->withErrors(['package_id' => 'Cannot change plan while there are overdue invoices. Please settle the outstanding balance first.'])
                    ->with('plan_change_warning', 'Outstanding payment must be cleared before changing plans.');
            }
            
            if ($hasUnpaidInvoices) {
                // If unpaid but not overdue, we can schedule the change for next cycle
                // but it won't take effect until current invoice is paid
                $this->schedulePlanChange($subscription, $newPackage, $effectiveOption, $hasUnpaidInvoices);
                
                DB::commit();
                
                return redirect()->route('subscriptions.show', $subscription->id)
                    ->with('warning', 'Plan change scheduled. New plan will take effect after current invoice is paid and next billing cycle starts.')
                    ->with('plan_change_info', [
                        'new_plan' => $newPackage->name,
                        'effective_date' => $effectiveOption === 'immediate' ? 'After payment' : 'Next billing cycle',
                        'pending_payment' => true
                    ]);
            }
            
            // If no unpaid invoices, we can process the plan change immediately
            if ($effectiveOption === 'immediate') {
                // Check if subscription is mid-cycle
                $isMidCycle = $now->between($subscription->start_date, $subscription->end_date);
                
                if ($isMidCycle) {
                    // Calculate proration
                    $prorationAmount = $this->calculateProration($subscription, $newPackage);
                    
                    // Create adjustment invoice for proration
                    if ($prorationAmount > 0) {
                        $this->createProrationInvoice($subscription, $newPackage, $prorationAmount);
                    }
                }
                
                // Update subscription with new plan
                $end = $this->calculateEndDate($start, $newPackage->billing_cycle);
                $nextBillingDate = $this->calculateNextBillingDate($subscription, $start, $end, $newPackage, true);
                
                $subscription->update([
                    'package_id'        => $newPackage->id,
                    'start_date'        => $start->toDateString(),
                    'end_date'          => $end->toDateString(),
                    'next_billing_date' => $nextBillingDate->toDateString(),
                    'pending_package_id' => null, // Clear any pending changes
                    'updated_by'        => Auth::id(),
                ]);
                
                // Create invoice for new plan
                $this->createNewPlanInvoice($subscription, $newPackage, $start, $end);
                
                $message = "Plan changed from {$currentPackage->name} to {$newPackage->name} effective immediately.";
                
            } else {
                // Schedule for next cycle
                $this->schedulePlanChange($subscription, $newPackage, 'next_cycle', false);
                
                $message = "Plan change scheduled. {$newPackage->name} will start on next billing cycle.";
            }
            
            // Log the plan change
            InvoiceLog::create([
                'subscription_id' => $subscription->id,
                'user_id'         => Auth::id(),
                'action'          => 'plan_change',
                'message'         => "Plan change: {$currentPackage->name} → {$newPackage->name}",
                'details'         => json_encode([
                    'old_package' => $currentPackage->name,
                    'new_package' => $newPackage->name,
                    'effective_option' => $effectiveOption,
                    'has_unpaid_invoices' => $hasUnpaidInvoices,
                    'has_overdue_invoices' => $hasOverdueInvoices,
                ]),
            ]);
            
            DB::commit();
            
            return redirect()->route('subscriptions.show', $subscription->id)
                ->with('success', $message);
                
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Schedule a plan change for future implementation
     */
    private function schedulePlanChange($subscription, $newPackage, $effectiveOption, $requiresPayment)
    {
        // Create plan change request
        PlanChangeRequest::create([
            'subscription_id' => $subscription->id,
            'old_package_id'  => $subscription->package_id,
            'new_package_id'  => $newPackage->id,
            'effective_date'  => $effectiveOption === 'immediate' ? now() : Carbon::parse($subscription->end_date)->addDay(),
            'effective_option' => $effectiveOption,
            'requires_payment' => $requiresPayment,
            'status'          => 'pending',
            'requested_by'    => Auth::id(),
        ]);
        
        // Set pending package on subscription
        $subscription->update([
            'pending_package_id' => $newPackage->id,
        ]);
    }

    /**
     * Calculate proration amount for immediate plan changes
     */
    private function calculateProration($subscription, $newPackage)
    {
        $currentPackage = $subscription->package;
        $now = Carbon::now();
        
        // Calculate days used in current cycle
        $cycleStart = Carbon::parse($subscription->start_date);
        $cycleEnd = Carbon::parse($subscription->end_date);
        $totalDaysInCycle = $cycleStart->diffInDays($cycleEnd) + 1;
        $daysUsed = $cycleStart->diffInDays($now) + 1;
        
        // Calculate unused days
        $unusedDays = $totalDaysInCycle - $daysUsed;
        
        // Calculate daily rates
        $currentDailyRate = $currentPackage->price / $totalDaysInCycle;
        $newDailyRate = $newPackage->price / $totalDaysInCycle;
        
        // Calculate proration (could be credit or charge)
        $prorationAmount = ($newDailyRate - $currentDailyRate) * $unusedDays;
        
        return max($prorationAmount, 0); // Only charge if new plan is more expensive
    }

    /**
     * Create proration invoice
     */
    private function createProrationInvoice($subscription, $newPackage, $prorationAmount)
    {
        $invoice = Invoice::create([
            'subscription_id'  => $subscription->id,
            'customer_id'      => $subscription->customer_id,
            'customer_name'    => $subscription->customer->user->name ?? null,
            'customer_contact' => $subscription->customer->user->email ?? null,
            'invoice_no'       => 'INV-' . str_pad((Invoice::max('id') + 1), 6, '0', STR_PAD_LEFT),
            'amount_due'       => $prorationAmount,
            'due_date'         => $subscription->end_date,
            'billing_date'     => now()->toDateString(),
            'description'      => "Proration adjustment - Plan change from {$subscription->package->name} to {$newPackage->name}",
            'status'           => 'unpaid',
            'is_recurring'     => false,
            'created_by'       => Auth::id(),
        ]);
        
        return $invoice;
    }

    /**
     * Create invoice for new plan
     */
    private function createNewPlanInvoice($subscription, $newPackage, $start, $end)
    {
        $invoice = Invoice::create([
            'subscription_id'  => $subscription->id,
            'customer_id'      => $subscription->customer_id,
            'customer_name'    => $subscription->customer->user->name ?? null,
            'customer_contact' => $subscription->customer->user->email ?? null,
            'invoice_no'       => 'INV-' . str_pad((Invoice::max('id') + 1), 6, '0', STR_PAD_LEFT),
            'period_start'     => $start->toDateString(),
            'period_end'       => $end->toDateString(),
            'amount_due'       => $newPackage->price,
            'due_date'         => $end->toDateString(),
            'billing_date'     => now()->toDateString(),
            'description'      => "New plan: {$newPackage->name}",
            'status'           => 'unpaid',
            'is_recurring'     => true,
            'created_by'       => Auth::id(),
        ]);
        
        return $invoice;
    }

    /**
     * Handle reconnection logic
     */
    private function handleReconnection($subscription, $reconnectionType)
    {
        // Create service request for reconnection
        $serviceRequest = \App\Models\ServiceRequest::create([
            'customer_id' => $subscription->customer_id,
            'subscription_id' => $subscription->id,
            'customer_name' => $subscription->customer->user->name ?? 'Unknown Customer',
            'phone' => $subscription->customer->user->phone ?? null,
            'email' => $subscription->customer->user->email ?? null,
            'address' => $subscription->customer->address ?? 'Address not provided',
            'service_type' => 'Reconnection Service',
            'notes' => "Reconnection request for subscription SUB-" . str_pad($subscription->id, 6, '0', STR_PAD_LEFT) . 
                      ". Package: " . ($subscription->package->name ?? 'N/A') .
                      ". Type: " . $reconnectionType,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        // Geocode the address if possible
        $serviceRequest->geocodeAddress();

        // Update subscription service status to pending reconnection
        $subscription->update([
            'service_status' => 'pending_reconnection',
        ]);

        return $serviceRequest;
    }

    /**
     * Process pending plan changes when invoice is paid
     * This should be called from InvoiceController when an invoice is paid
     */
    public function processPendingPlanChange($subscriptionId)
    {
        $subscription = Subscription::with(['pendingPackage', 'planChangeRequests' => function($query) {
            $query->where('status', 'pending')->latest();
        }])->findOrFail($subscriptionId);

        if (!$subscription->pending_package_id || $subscription->planChangeRequests->isEmpty()) {
            return false;
        }

        DB::beginTransaction();
        try {
            $planChangeRequest = $subscription->planChangeRequests->first();
            $newPackage = Package::findOrFail($subscription->pending_package_id);
            
            // Update subscription with new plan
            $start = now();
            $end = $this->calculateEndDate($start, $newPackage->billing_cycle);
            
            $subscription->update([
                'package_id'        => $newPackage->id,
                'start_date'        => $start->toDateString(),
                'end_date'          => $end->toDateString(),
                'next_billing_date' => $end->toDateString(),
                'pending_package_id' => null,
            ]);
            
            // Update plan change request status
            $planChangeRequest->update([
                'status' => 'completed',
                'completed_at' => now(),
                'completed_by' => Auth::id(),
            ]);
            
            // Create invoice for new plan
            $this->createNewPlanInvoice($subscription, $newPackage, $start, $end);
            
            // Log the plan change completion
            InvoiceLog::create([
                'subscription_id' => $subscription->id,
                'user_id'         => Auth::id(),
                'action'          => 'plan_change_completed',
                'message'         => "Pending plan change to {$newPackage->name} completed after payment",
                'details'         => json_encode([
                    'old_package' => $subscription->package->name,
                    'new_package' => $newPackage->name,
                    'plan_change_request_id' => $planChangeRequest->id,
                ]),
            ]);
            
            DB::commit();
            return true;
            
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to process pending plan change: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cancel pending plan change
     */
    public function cancelPendingPlanChange($id)
    {
        $subscription = Subscription::findOrFail($id);
        
        if (!$subscription->pending_package_id) {
            return back()->with('error', 'No pending plan change to cancel.');
        }
        
        DB::beginTransaction();
        try {
            $oldPendingPackageId = $subscription->pending_package_id;
            $pendingPackage = Package::find($oldPendingPackageId);
            
            // Update any pending plan change requests
            PlanChangeRequest::where('subscription_id', $subscription->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancelled_by' => Auth::id(),
                ]);
            
            // Clear pending package
            $subscription->update([
                'pending_package_id' => null,
            ]);
            
            // Log the cancellation
            InvoiceLog::create([
                'subscription_id' => $subscription->id,
                'user_id'         => Auth::id(),
                'action'          => 'plan_change_cancelled',
                'message'         => "Pending plan change to {$pendingPackage->name} cancelled",
                'details'         => json_encode([
                    'pending_package' => $pendingPackage->name,
                ]),
            ]);
            
            DB::commit();
            
            return redirect()->route('subscriptions.show', $subscription->id)
                ->with('success', 'Pending plan change cancelled successfully.');
                
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to cancel pending plan change: ' . $e->getMessage());
            return back()->with('error', 'Failed to cancel pending plan change.');
        }
    }

    /**
     * Calculate end date based on billing cycle
     */
    private function calculateEndDate(Carbon $start, string $billingCycle): Carbon
    {
        return match (strtolower($billingCycle)) {
            'yearly'    => $start->copy()->addYear(),
            'quarterly' => $start->copy()->addMonths(3),
            'weekly'    => $start->copy()->addWeek(),
            default     => $start->copy()->addMonth(),
        };
    }

    /**
     * Calculate next billing date based on subscription state
     */
    private function calculateNextBillingDate($subscription, Carbon $start, Carbon $end, $newPackage, bool $isPackageChange): Carbon
    {
        $now = Carbon::now();

        // If subscription hasn't started yet, calculate from start date
        if ($now->lt($start)) {
            return $this->calculateEndDate($start, $newPackage->billing_cycle);
        }

        // If package changed, recalculate from current date or keep existing cycle
        if ($isPackageChange) {
            // For package changes, keep the current billing cycle but update the package
            return $end;
        }

        // For other changes, maintain existing billing cycle
        return Carbon::parse($subscription->next_billing_date);
    }

    /**
     * Handle trial period logic for subscription updates
     */
    private function handleTrialPeriod($subscription, $newPackage, Carbon $start, bool $isPackageChange): ?Carbon
    {
        // If package changed and new package has trial days, start new trial
        if ($isPackageChange && $newPackage->free_trial_days > 0) {
            return $start->copy()->addDays($newPackage->free_trial_days);
        }

        // If same package, keep existing trial if still valid
        if (!$isPackageChange && $subscription->trial_end_date) {
            $trialEnd = Carbon::parse($subscription->trial_end_date);
            return $trialEnd->isFuture() ? $trialEnd : null;
        }

        return null;
    }

    /**
     * Determine service status based on subscription state
     */
    private function determineServiceStatus($subscription, string $newStatus, ?Carbon $trialEndDate, ?string $newServiceStatus, string $changeType): string
    {
        // If in trial period, service should be active
        if ($trialEndDate && $trialEndDate->isFuture()) {
            return 'active';
        }

        // Handle reconnection
        if ($changeType === 'reconnect_current' || $changeType === 'reconnect_new') {
            return 'pending_reconnection';
        }

        // Use specified service status if provided
        if ($newServiceStatus) {
            return $newServiceStatus;
        }

        // Keep current service status unless status changed to cancelled
        if ($newStatus === 'cancelled') {
            return 'disconnected';
        }

        return $subscription->service_status;
    }

    // The rest of your existing methods (destroy, renew, disconnectOverdue, reconnect, etc.) remain the same
    // ... [Keep all your existing methods from line 657 onwards] ...

    /** 🔹 Cancel subscription with confirmation */
    public function destroy($id)
    {
        Log::info('🔴 Subscription cancellation initiated for ID: ' . $id, [
            'user' => Auth::user() ? Auth::user()->name : 'Unknown',
            'method' => request()->method(),
            'ip' => request()->ip(),
        ]);

        try {
            $subscription = Subscription::with(['customer.user', 'package'])->findOrFail($id);
            
            Log::info('📊 Subscription found:', [
                'id' => $subscription->id,
                'status' => $subscription->status,
                'customer' => $subscription->customer->user->name ?? 'Unknown',
            ]);

            // Validate subscription can be cancelled
            if ($subscription->status !== 'active') {
                $errorMsg = "Only active subscriptions can be cancelled. Current status: {$subscription->status}";
                Log::warning($errorMsg);
                
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMsg
                    ], 400);
                }
                return redirect()->route('subscriptions.index')
                    ->with('error', $errorMsg);
            }

            DB::beginTransaction();
            Log::info('✅ Starting transaction for subscription cancellation...');

            // Update subscription status to cancelled
            $subscription->update([
                'status' => 'cancelled',
                'service_status' => 'disconnected',
                'cancelled_at' => now(),
                'cancelled_by' => Auth::id(),
                'pending_package_id' => null, // Clear any pending plan changes
            ]);

            // Cancel any pending invoices
            $cancelledInvoices = $subscription->invoices()
                ->whereIn('status', ['unpaid', 'pending'])
                ->update(['status' => 'cancelled']);
                
            Log::info('📄 Invoices cancelled: ' . $cancelledInvoices);

            // Cancel any pending plan change requests
            PlanChangeRequest::where('subscription_id', $subscription->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancelled_by' => Auth::id(),
                ]);

            // Log the cancellation
            InvoiceLog::create([
                'subscription_id' => $subscription->id,
                'invoice_id'      => null,
                'user_id'         => Auth::id(),
                'action'          => 'cancelled',
                'message'         => "Subscription cancelled by " . (Auth::user()->name ?? 'Admin') . ". Service deactivated.",
                'details'         => json_encode([
                    'old_status' => 'active',
                    'new_status' => 'cancelled',
                    'cancelled_at' => now()->toDateTimeString(),
                ]),
            ]);

            DB::commit();
            Log::info('✅ Transaction committed successfully');

            // Prepare response data
            $customerName = $subscription->customer->user->name ?? 'Unknown Customer';
            $subscriptionNumber = 'SUB-' . str_pad($subscription->id, 6, '0', STR_PAD_LEFT);
            $successMessage = "Subscription {$subscriptionNumber} for {$customerName} has been cancelled successfully.";

            Log::info('✅ Subscription cancelled successfully', [
                'subscription_id' => $subscription->id,
                'customer' => $customerName,
                'user' => Auth::user()->name ?? 'Unknown',
            ]);

            // Always redirect with flash message
            return redirect()
                ->route('subscriptions.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ Failed to cancel subscription #{$id}: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString(),
                'user' => Auth::user()->name ?? 'Unknown',
            ]);

            $errorMessage = 'Failed to cancel subscription. Please try again.';
            
            // Check if this is an AJAX request
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error_details' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            // For regular form submissions, redirect back with error
            return redirect()
                ->route('subscriptions.index')
                ->with('error', $errorMessage);
        }
    }

    /** 🔹 Renew subscription with confirmation */
    public function renew($id)
    {
        Log::info('🔄 Subscription renewal initiated for ID: ' . $id, [
            'user' => Auth::user() ? Auth::user()->name : 'Unknown',
        ]);

        try {
            DB::beginTransaction();

            $subscription = Subscription::with('package')->findOrFail($id);

            if ($subscription->status !== 'expired') {
                $errorMsg = 'Only expired subscriptions can be renewed. Current status: ' . $subscription->status;
                Log::warning($errorMsg);
                
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMsg
                    ], 400);
                }
                return redirect()->route('subscriptions.index')
                    ->with('error', $errorMsg);
            }

            $package = $subscription->package;
            $now = Carbon::now();

            $newEnd = match($package->billing_cycle ?? 'monthly') {
                'yearly' => $now->copy()->addYear(),
                'quarterly' => $now->copy()->addMonths(3),
                default => $now->copy()->addMonth(),
            };

            $subscription->update([
                'status'            => 'active',
                'end_date'          => $newEnd->toDateString(),
                'next_billing_date' => $newEnd->toDateString(),
                'service_status'    => 'active',
                'renewed_at'        => now(),
                'renewed_by'        => Auth::id(),
                'pending_package_id' => null, // Clear any pending changes
            ]);

            $invoice = $subscription->invoices()->create([
                'subscription_id'  => $subscription->id,
                'customer_id'      => $subscription->customer_id,
                'customer_name'    => $subscription->customer->user->name ?? null,
                'customer_contact' => $subscription->customer->user->email ?? null,
                'invoice_no'       => 'INV-' . str_pad((Invoice::max('id') + 1), 6, '0', STR_PAD_LEFT),
                'amount_due'       => $package->price ?? 0,
                'due_date'         => $newEnd->toDateString(),
                'billing_date'     => $now->toDateString(),
                'description'      => 'Renewal Invoice - ' . ($package->name ?? ''),
                'status'           => 'unpaid',
                'is_recurring'     => true,
                'created_by'       => Auth::id(),
            ]);

            InvoiceLog::create([
                'subscription_id' => $subscription->id,
                'invoice_id'      => $invoice->id,
                'user_id'         => Auth::id(),
                'action'          => 'renewed',
                'message'         => "Subscription renewed by " . (Auth::user()->name ?? 'Admin') . ". Invoice #{$invoice->invoice_no} created.",
                'details'         => json_encode([
                    'old_end_date' => $subscription->getOriginal('end_date'),
                    'new_end_date' => $newEnd->toDateString(),
                    'invoice_number' => $invoice->invoice_no,
                ]),
            ]);

            DB::commit();

            $subscriptionNumber = 'SUB-' . str_pad($subscription->id, 6, '0', STR_PAD_LEFT);
            $successMessage = "Subscription {$subscriptionNumber} has been renewed successfully. Invoice #{$invoice->invoice_no} created.";

            Log::info('✅ Subscription renewed successfully', [
                'subscription_id' => $subscription->id,
                'invoice_number' => $invoice->invoice_no,
                'user' => Auth::user()->name ?? 'Unknown',
            ]);

            // Check if this is an AJAX request
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'data' => [
                        'id' => $subscription->id,
                        'status' => 'active',
                        'subscription_number' => $subscriptionNumber,
                        'invoice_number' => $invoice->invoice_no,
                        'redirect_url' => route('subscriptions.show', $id)
                    ]
                ]);
            }

            return redirect()->route('subscriptions.show', $id)
                ->with('success', $successMessage);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('❌ Subscription renew error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user' => Auth::user()->name ?? 'Unknown',
            ]);

            $errorMessage = 'Failed to renew subscription. Please try again.';
            
            // Check if this is an AJAX request
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error_details' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return back()->with('error', $errorMessage);
        }
    }

    public function disconnectOverdue()
    {
        Log::info('🔌 Starting automatic disconnection of overdue subscriptions', [
            'user' => Auth::user() ? Auth::user()->name : 'System',
        ]);

        $overdueSubscriptions = Subscription::with('package')
            ->where('status', 'active')
            ->whereHas('invoices', function ($query) {
                $query->where('status', 'overdue')
                      ->where('due_date', '<', now());
            })
            ->whereHas('package', function ($query) {
                $query->where('auto_disconnect_overdue', true);
            })
            ->get();

        $disconnectedCount = 0;

        foreach ($overdueSubscriptions as $subscription) {
            DB::beginTransaction();
            try {
                $subscription->update([
                    'service_status' => 'disconnected',
                    'disconnected_at' => now(),
                    'disconnected_by' => Auth::id(),
                    'pending_package_id' => null, // Clear pending plan changes when disconnected
                ]);

                // Cancel any pending plan change requests
                PlanChangeRequest::where('subscription_id', $subscription->id)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                        'cancelled_by' => Auth::id(),
                    ]);

                InvoiceLog::create([
                    'subscription_id' => $subscription->id,
                    'user_id'         => Auth::id(),
                    'action'          => 'auto_disconnected',
                    'message'         => "Service automatically disconnected due to overdue payment",
                    'details'         => json_encode([
                        'reason' => 'overdue_payment',
                        'disconnected_at' => now()->toDateTimeString(),
                    ]),
                ]);

                $disconnectedCount++;
                DB::commit();
                
                Log::info('✅ Disconnected subscription', [
                    'subscription_id' => $subscription->id,
                    'customer' => $subscription->customer->user->name ?? 'Unknown',
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('❌ Failed to disconnect subscription: ' . $e->getMessage(), [
                    'subscription_id' => $subscription->id,
                ]);
            }
        }

        $message = "{$disconnectedCount} overdue subscriptions have been disconnected.";
        Log::info('✅ ' . $message);

        return redirect()->route('subscriptions.index')
            ->with('success', $message);
    }

    // Add this method for debugging
    public function debugCancel($id)
    {
        try {
            $subscription = Subscription::findOrFail($id);
            
            return response()->json([
                'subscription_exists' => true,
                'subscription_id' => $subscription->id,
                'status' => $subscription->status,
                'can_be_cancelled' => $subscription->status === 'active',
                'route_name' => route('subscriptions.destroy', $id),
                'csrf_token' => csrf_token(),
                'user_role' => Auth::user()->role ?? 'guest',
                'is_ajax' => request()->ajax(),
                'method' => request()->method(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /** 🔹 Reconnect subscription service */
    public function reconnect($id)
    {
        Log::info('🔌 Subscription reconnection initiated for ID: ' . $id, [
            'user' => Auth::user() ? Auth::user()->name : 'Unknown',
        ]);

        try {
            DB::beginTransaction();

            $subscription = Subscription::with(['customer.user', 'package'])->findOrFail($id);

            if ($subscription->service_status !== 'disconnected') {
                $errorMsg = 'Only disconnected subscriptions can be reconnected. Current service status: ' . $subscription->service_status;
                Log::warning($errorMsg);

                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMsg
                    ], 400);
                }
                return redirect()->route('subscriptions.index')
                    ->with('error', $errorMsg);
            }

            // Create service request for reconnection
            $serviceRequest = \App\Models\ServiceRequest::create([
                'customer_id' => $subscription->customer_id,
                'subscription_id' => $subscription->id,
                'customer_name' => $subscription->customer->user->name ?? 'Unknown Customer',
                'phone' => $subscription->customer->user->phone ?? null,
                'email' => $subscription->customer->user->email ?? null,
                'address' => $subscription->customer->address ?? 'Address not provided',
                'service_type' => 'Reconnection Service',
                'notes' => "Reconnection request for subscription SUB-" . str_pad($subscription->id, 6, '0', STR_PAD_LEFT) . ". Package: " . ($subscription->package->name ?? 'N/A'),
                'status' => 'pending',
                'created_by' => Auth::id(),
            ]);

            // Geocode the address if possible
            $serviceRequest->geocodeAddress();

            $subscription->update([
                'status' => 'pending',
                'service_status' => 'pending',
                'reconnected_at' => now(),
                'reconnected_by' => Auth::id(),
            ]);

            // Log the reconnection
            InvoiceLog::create([
                'subscription_id' => $subscription->id,
                'invoice_id'      => null,
                'user_id'         => Auth::id(),
                'action'          => 'reconnected',
                'message'         => "Reconnection initiated by " . (Auth::user()->name ?? 'Admin') . ". Service request #" . $serviceRequest->id . " created. Subscription status set to pending until service completion.",
                'details'         => json_encode([
                    'old_service_status' => 'disconnected',
                    'new_status' => 'pending',
                    'new_service_status' => 'pending',
                    'reconnected_at' => now()->toDateTimeString(),
                    'service_request_id' => $serviceRequest->id,
                ]),
            ]);

            DB::commit();

            $subscriptionNumber = 'SUB-' . str_pad($subscription->id, 6, '0', STR_PAD_LEFT);
            $customerName = $subscription->customer->user->name ?? 'Unknown Customer';
            $successMessage = "Subscription {$subscriptionNumber} for {$customerName} has been reconnected successfully. Service request #{$serviceRequest->id} created.";

            Log::info('✅ Subscription reconnected successfully', [
                'subscription_id' => $subscription->id,
                'service_request_id' => $serviceRequest->id,
                'customer' => $customerName,
                'user' => Auth::user()->name ?? 'Unknown',
            ]);

            // Check if this is an AJAX request
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'data' => [
                        'id' => $subscription->id,
                        'service_status' => 'active',
                        'subscription_number' => $subscriptionNumber,
                        'service_request_id' => $serviceRequest->id,
                        'redirect_url' => route('subscriptions.show', $id)
                    ]
                ]);
            }

            return redirect()->route('subscriptions.show', $id)
                ->with('success', $successMessage);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('❌ Subscription reconnect error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user' => Auth::user()->name ?? 'Unknown',
            ]);

            $errorMessage = 'Failed to reconnect subscription. Please try again.';

            // Check if this is an AJAX request
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error_details' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return back()->with('error', $errorMessage);
        }
    }

    /**
     * Check if request is a form submission (not AJAX)
     */
    private function isFormSubmission()
    {
        return !request()->expectsJson() && !request()->ajax() && request()->isMethod('delete');
    }
}