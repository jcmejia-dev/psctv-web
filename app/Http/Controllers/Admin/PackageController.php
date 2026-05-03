<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the packages.
     */
    public function index(Request $request)
    {
        $packages = Package::orderBy('created_at', 'desc')->paginate(10);

        // Calculate summary counts
        $activeCount = Package::where('is_active', true)->count();
        $internetCount = Package::where('type', 'internet')->count();
        $cableCount = Package::where('type', 'cable')->count();

        return view('packages.index', compact('packages', 'activeCount', 'internetCount', 'cableCount'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        return view('packages.create');
    }

    /**
     * Store a newly created package in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                   => 'required|string|max:255',
            'description'            => 'nullable|string',
            'type'                   => 'required|in:internet,cable',
            'speed_mbps'             => 'nullable|integer',
            'channels'               => 'nullable|integer',
            'price'                  => 'required|numeric|min:0',
            'billing_cycle'          => 'required|in:monthly,quarterly,yearly',
            'free_trial_days'        => 'nullable|integer|min:0',
            'reconnection_fee'       => 'nullable|numeric|min:0',
            'auto_disconnect_overdue' => 'boolean',
            'is_active'              => 'boolean',
        ]);

        $package = Package::create($validated);

        // Ensure billing rules exist for the new package if it's active
        if ($package->is_active) {
            \App\Models\BillingRule::ensureDefaultsExist();
        }

        return redirect()->route('packages.index')
            ->with('success', 'Package created successfully.');
    }



    public function duplicate(Package $package)
{
    $newPackage = $package->replicate();
    $newPackage->code = $package->code . '-COPY';
    $newPackage->name = $package->name . ' (Copy)';
    $newPackage->is_active = false; // Deactivate duplicate by default
    $newPackage->save();

    return redirect()->route('packages.edit', $newPackage)
        ->with('success', 'Package duplicated successfully. Please review and save.');
}
   

public function toggleStatus(Package $package)
{
    $package->update([
        'is_active' => !$package->is_active
    ]);

    $status = $package->is_active ? 'activated' : 'deactivated';
    
    return redirect()->route('packages.index')
        ->with('success', "Package {$package->name} has been {$status} successfully.");
}

    /**
     * Display the specified package.
     */
    public function show(Package $package)
    {
        $package->load('billingRule');
        return view('packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }

    /**
     * Update the specified package in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name'                   => 'required|string|max:255',
            'description'            => 'nullable|string',
            'type'                   => 'required|in:cable,internet',
            'speed_mbps'             => 'nullable|integer',
            'channels'               => 'nullable|integer',
            'price'                  => 'required|numeric|min:0',
            'billing_cycle'          => 'required|in:monthly,quarterly,yearly',
            'free_trial_days'        => 'nullable|integer|min:0',
            'reconnection_fee'       => 'nullable|numeric|min:0',
            'auto_disconnect_overdue' => 'boolean',
            'is_active'              => 'boolean',
        ]);

        $package->update($validated);

        return redirect()->route('packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified package from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('packages.index')
            ->with('success', 'Package deleted successfully.');
    }
}
