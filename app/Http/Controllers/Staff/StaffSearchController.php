<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Technician;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Auth;

class StaffSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:staff');
    }

    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = collect();
        $user = Auth::user();

        // =========================
        // 🔹 Search Customers (if staff has access)
        // =========================
        if ($user->hasModuleAccess('customers')) {
            $customers = Customer::with('user')
                ->where(function($query) use ($q) {
                    $query->whereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%"))
                          ->orWhere('account_number', 'like', "%{$q}%")
                          ->orWhere('phone', 'like', "%{$q}%");
                })
                ->take(5)
                ->get()
                ->map(fn($c) => [
                    'id' => $c->id,
                    'name' => $c->user->name ?? 'Unnamed Customer',
                    'type' => 'Customer',
                    'icon' => '👥',
                    'url' => route('staff.customers.show', $c->id),
                ]);

            $results = $results->merge($customers);
        }

        // =========================
        // 🔹 Search Technicians (if staff has access)
        // =========================
        if ($user->hasModuleAccess('technicians')) {
            $technicians = Technician::where(function($t) use ($q) {
                    $t->where('full_name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%");
                })
                ->take(5)
                ->get()
                ->map(fn($t) => [
                    'id' => $t->id,
                    'name' => $t->full_name,
                    'type' => 'Technician',
                    'icon' => '🛠️',
                    'url' => route('staff.technicians.show', $t->id),
                ]);

            $results = $results->merge($technicians);
        }

        // =========================
        // 🔹 Search Service Requests (if staff has access)
        // =========================
        if ($user->hasModuleAccess('service_requests')) {
            $requests = ServiceRequest::where(function($r) use ($q) {
                    $r->where('service_type', 'like', "%{$q}%")
                      ->orWhere('customer_name', 'like', "%{$q}%")
                      ->orWhere('id', 'like', "%{$q}%");
                })
                ->take(5)
                ->get()
                ->map(fn($r) => [
                    'id' => $r->id,
                    'name' => "Request #{$r->id} – {$r->service_type}",
                    'type' => 'Service Request',
                    'icon' => '📋',
                    'url' => route('staff.service_requests.show', $r->id),
                ]);

            $results = $results->merge($requests);
        }

        return response()->json($results);
    }
}
