<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Technician;
use App\Models\ServiceRequest;
use App\Models\User;

class AdminSearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = collect();

        // =========================
        // 🔹 Search Customers
        // =========================
        $customers = Customer::with('user')
            ->where(function($query) use ($q) {
                $query->whereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%"))
                      ->orWhere('account_number', 'like', "%{$q}%");
            })
            ->take(5)
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->user->name ?? 'Unnamed Customer',
                'type' => 'Customer',
                'icon' => '👥',
                'url' => route('customers.show', $c->id),
            ]);

        // =========================
        // 🔹 Search Technicians
        // =========================
        $technicians = Technician::where(function($t) use ($q) {
                $t->where('full_name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            })
            ->take(5)
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->full_name,
                'type' => 'Technician',
                'icon' => '🛠️',
                'url' => route('technicians.show', $t->id),
            ]);

        // =========================
        // 🔹 Search Staff
        // =========================
        $staff = User::where('role', 'staff')
            ->where(function($u) use ($q) {
                $u->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            })
            ->take(5)
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'type' => 'Staff',
                'icon' => '🧑‍💼',
                'url' => route('staff.show', $s->id), // Make sure this route exists
            ]);

        // =========================
        // 🔹 Search Service Requests
        // =========================
        $requests = ServiceRequest::where(function($r) use ($q) {
                $r->where('service_type', 'like', "%{$q}%")
                  ->orWhere('customer_name', 'like', "%{$q}%");
            })
            ->take(5)
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'name' => "Request #{$r->id} – {$r->service_type}",
                'type' => 'Service Request',
                'icon' => '📋',
                'url' => route('service_requests.show', $r->id),
            ]);

        // Merge all results
        $results = $results->merge($customers)
                           ->merge($technicians)
                           ->merge($staff)
                           ->merge($requests);

        return response()->json($results);
    }
}