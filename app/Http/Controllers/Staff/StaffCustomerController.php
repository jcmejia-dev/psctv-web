<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:staff'); // Adjust to your staff middleware (e.g., 'can:view-staff-customers')
    }

    /** List customers (staff view) */
    public function index(Request $request)
    {
        $query = Customer::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                  ->orWhere('account_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $customers = $query->latest()->paginate(10);

        return view('staff.customers.index', compact('customers'));
    }

    /** Show customer details */
    public function show(Customer $customer)
    {
        // Optional: Check if staff can view this customer
        // if (!Auth::user()->canViewCustomer($customer)) abort(403);

        $customer->load([
            'user',
            'subscriptions.package',
            'subscriptions.invoices.payments',
            'serviceRequests.technician.user'
        ]);

        $totalPaid = $customer->subscriptions->flatMap->invoices->flatMap->payments->sum('amount');
        $outstandingBalance = $customer->subscriptions->flatMap->invoices
            ->where('status', 'unpaid')->sum('amount_due');

        return view('staff.customers.show', compact('customer', 'totalPaid', 'outstandingBalance'));
    }

    /** Edit customer form (limited fields for staff) */
    public function edit(Customer $customer)
    {
        // Optional: Permission check
        // if (!Auth::user()->canEditCustomer($customer)) abort(403);

        return view('staff.customers.edit', compact('customer'));
    }

    /** Update customer (limited fields, no password for staff) */
    public function update(Request $request, Customer $customer)
    {
        // Optional: Permission check
        // if (!Auth::user()->canEditCustomer($customer)) abort(403);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $customer->user_id,
            'status' => 'required|in:active,inactive,suspended,pending',
        ]);

        try {
            DB::transaction(function () use ($request, $customer) {
                $customer->user->update([
                    'name'  => $request->name,
                    'email' => $request->email,
                ]);

                // Staff cannot change passwords; remove if present in request
                $customer->update([
                    'status'    => $request->status,
                    'address'   => $request->address,
                    'city'      => $request->city,
                    'province'  => $request->province,
                    'zip_code'  => $request->zip_code,
                    'latitude'  => $request->latitude,
                    'longitude' => $request->longitude,
                    'phone'     => $request->phone,
                    'dob'       => $request->dob,
                    'gender'    => $request->gender,
                ]);
            });

            return redirect()->route('staff.customers.show', $customer->id)->with('success', 'Customer updated successfully.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Error updating customer: ' . $e->getMessage()]);
        }
    }
}
