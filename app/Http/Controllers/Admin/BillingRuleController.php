<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillingRule;
use App\Models\Package;
use Illuminate\Support\Facades\Log;

class BillingRuleController extends Controller
{
    /**
     * Display a listing of billing rules.
     */
    public function index(Request $request)
    {
        // Ensure default billing rules exist for all active packages
        BillingRule::ensureDefaultsExist();

        $query = BillingRule::with('package');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('package', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $billingRules = $query->latest()->paginate(10);

        return view('billing-rules.index', compact('billingRules'));
    }

    /**
     * Show the form for creating a new billing rule.
     */
    public function create()
    {
        $packages = Package::where('is_active', true)->get();
        return view('billing-rules.create', compact('packages'));
    }

    /**
     * Store a newly created billing rule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id|unique:billing_rules,package_id',
            'grace_period_days' => 'required|integer|min:0|max:365',
            'late_fee_amount' => 'required|numeric|min:0|max:999999.99',
            'late_fee_frequency' => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'suspension_days' => 'required|integer|min:0|max:365',
            'disconnection_days' => 'required|integer|min:0|max:365',
            'suspension_fee' => 'nullable|numeric|min:0|max:999999.99',
            'reconnection_fee' => 'nullable|numeric|min:0|max:999999.99',
            'auto_apply_penalties' => 'boolean',
        ]);

        BillingRule::create($validated);

        Log::info('Billing rule created', [
            'package_id' => $validated['package_id'],
            'user' => auth()->user()->name ?? 'Unknown'
        ]);

        return redirect()->route('billing-rules.index')
            ->with('success', 'Billing rule created successfully.');
    }

    /**
     * Display the specified billing rule.
     */
    public function show(BillingRule $billingRule)
    {
        $billingRule->load('package');
        return view('billing-rules.show', compact('billingRule'));
    }

    /**
     * Show the form for editing the specified billing rule.
     */
    public function edit(BillingRule $billingRule)
    {
        $packages = Package::where('is_active', true)->get();
        return view('billing-rules.edit', compact('billingRule', 'packages'));
    }

    /**
     * Update the specified billing rule.
     */
    public function update(Request $request, BillingRule $billingRule)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id|unique:billing_rules,package_id,' . $billingRule->id,
            'grace_period_days' => 'required|integer|min:0|max:365',
            'late_fee_amount' => 'required|numeric|min:0|max:999999.99',
            'late_fee_frequency' => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'suspension_days' => 'required|integer|min:0|max:365',
            'disconnection_days' => 'required|integer|min:0|max:365',
            'suspension_fee' => 'nullable|numeric|min:0|max:999999.99',
            'reconnection_fee' => 'nullable|numeric|min:0|max:999999.99',
            'auto_apply_penalties' => 'boolean',
        ]);

        $billingRule->update($validated);

        Log::info('Billing rule updated', [
            'billing_rule_id' => $billingRule->id,
            'package_id' => $validated['package_id'],
            'user' => auth()->user()->name ?? 'Unknown'
        ]);

        return redirect()->route('billing-rules.show', $billingRule)
            ->with('success', 'Billing rule updated successfully.');
    }

    /**
     * Remove the specified billing rule.
     */
    public function destroy(BillingRule $billingRule)
    {
        $billingRule->delete();

        Log::info('Billing rule deleted', [
            'billing_rule_id' => $billingRule->id,
            'package_name' => $billingRule->package->name ?? 'Unknown',
            'user' => auth()->user()->name ?? 'Unknown'
        ]);

        return redirect()->route('billing-rules.index')
            ->with('success', 'Billing rule deleted successfully.');
    }
}
