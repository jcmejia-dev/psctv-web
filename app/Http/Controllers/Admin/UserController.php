<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Technician;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;     


class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        $users->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.add');
    }

    public function store(Request $request)
    {
        // Base validation
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => ['required', Rule::in(['admin', 'staff', 'technician', 'customer'])],
            'module_access'    => 'nullable|array',
            'module_access.*'  => 'exists:staff_modules,id',
        ];

        // Add conditional validation for customer
        if ($request->role === 'customer') {
            $rules = array_merge($rules, [
                'customer_phone' => 'nullable|string|max:20',
                'customer_status' => ['required', Rule::in(['active', 'pending', 'inactive', 'suspended'])],
                'customer_dob' => 'nullable|date|before:today',
                'customer_gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
                'customer_region' => 'nullable|string|max:255',
                'customer_province' => 'nullable|string|max:255',
                'customer_municipality' => 'nullable|string|max:255',
                'customer_barangay' => 'nullable|string|max:255',
                'customer_street' => 'nullable|string|max:255',
                'customer_zip_code' => 'nullable|string|max:10',
                'customer_latitude' => 'nullable|numeric|between:-90,90',
                'customer_longitude' => 'nullable|numeric|between:-180,180',
            ]);
        }

        // Add conditional validation for technician
        if ($request->role === 'technician') {
            $rules = array_merge($rules, [
                'technician_phone' => 'nullable|string|max:20',
                'technician_service_area' => 'nullable|string|max:255',
                'technician_specialization' => 'nullable|string|max:255',
                'technician_date_hire' => 'required|date|before_or_equal:today',
                'technician_address' => 'nullable|string|max:500',
            ]);
        }

        $request->validate($rules);

        DB::transaction(function () use ($request) {
            // 🔹 Step 1: Auto-generate random password
            $password = \Illuminate\Support\Str::random(10);

            // 🔹 Step 2: Create user
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($password),
                'role'     => $request->role,
            ]);

            // 🔹 Step 3: If role is CUSTOMER → Create complete customer profile
            if ($request->role === 'customer') {
                Customer::create([
                    'user_id'       => $user->id,
                    'account_number'=> 'ACC-' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                    'phone'         => $request->customer_phone,
                    'status'        => $request->customer_status,
                    'dob'           => $request->customer_dob,
                    'gender'        => $request->customer_gender,
                    'region'        => $request->customer_region ?: 'Region XI – Davao Region',
                    'province'      => $request->customer_province ?: 'Davao del Norte',
                    'municipality'  => $request->customer_municipality ?: 'Panabo City',
                    'barangay'      => $request->customer_barangay,
                    'street'        => $request->customer_street,
                    'zip_code'      => $request->customer_zip_code ?: '8105',
                    'latitude'      => $request->customer_latitude,
                    'longitude'     => $request->customer_longitude,
                ]);
            }

            // 🔹 Step 4: If role is TECHNICIAN → Create complete technician profile
            if ($request->role === 'technician') {
                Technician::create([
                    'user_id'      => $user->id,
                    'full_name'    => $request->name,
                    'email'        => $request->email,
                    'phone'        => $request->technician_phone,
                    'technician_id'=> 'TECH-' . strtoupper(Str::random(6)),
                    'status'       => 'active',
                    'date_hire'    => $request->technician_date_hire,
                    'address'      => $request->technician_address,
                    'service_area' => $request->technician_service_area,
                    'specialization' => $request->technician_specialization,
                ]);
            }

            // 🔹 Step 5: Assign module access for STAFF
            if ($request->role === 'staff' && $request->has('module_access')) {
                $moduleAccess = $request->input('module_access', []);
                $allModules = \App\Models\StaffModule::all();

                foreach ($allModules as $module) {
                    \App\Models\UserStaffModuleAccess::create([
                        'user_id'         => $user->id,
                        'staff_module_id' => $module->id,
                        'has_access'      => in_array($module->id, $moduleAccess),
                    ]);
                }
            }

            // 🔹 Step 6: Send email with password
            Mail::to($user->email)
                ->send(new \App\Mail\SendPasswordMail($user->email, $password, $user->name));
        });

        return redirect()->route('users.index')
            ->with('success', ucfirst($request->role) . ' account created successfully with complete details and password sent to email!');
    }


    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Show module access management for staff
     */
    public function manageModuleAccess($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'staff') {
            return back()->withErrors(['error' => 'This user is not a staff member.']);
        }

        return redirect()->route('staff-module-access.edit', $user->id);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|confirmed|min:6',
            'role'     => ['required', Rule::in(['admin', 'staff', 'technician', 'customer'])],
        ]);

        // Prevent editing or converting customers
        if ($user->role === 'customer' || $request->role === 'customer') {
            return back()->withErrors([
                'role' => 'Customer roles cannot be edited or reassigned from this module.',
            ]);
        }

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => $request->filled('password')
                ? Hash::make($request->password)
                : $user->password,
        ]);

        return redirect()->route('users.edit', $user->id)->with('success', 'User updated successfully!');
    }

    /**
     * Send a new auto-generated password to the user's email.
     */
    public function sendPasswordReset($id)
    {
        $user = User::findOrFail($id);

        // Generate a new random password
        $newPassword = Str::random(10);

        // Update the user's password
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        // Send email with the new password
        Mail::to($user->email)
            ->send(new \App\Mail\SendPasswordMail($user->email, $newPassword, $user->name));

        // Check if request is AJAX
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'New password has been sent to the user\'s email address.'
            ]);
        }

        return redirect()->back()->with('success', 'New password has been sent to the user\'s email address.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'customer') {
            return back()->withErrors([
                'role' => 'Customer accounts cannot be deleted from here.',
            ]);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
