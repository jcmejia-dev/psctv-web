<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Technician;

class StaffTechnicianController extends Controller
{
    /** 🔹 View all technicians */
    public function index()
    {
        $query = Technician::query();

        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $technicians = $query->orderBy('id', 'desc')->paginate(10);

        return view('staff.technicians.index', compact('technicians'));
    }

    /** 🔹 View technician details */
    public function show(Technician $technician)
    {
        $technician->load(['serviceRequests.customer.user']);

        $technician->serviceRequests->each(function ($sr) {
            $registered = $sr->customer?->user?->name;
            $walkIn = $sr->customer_name;

            $sr->display_customer_name = $registered ?? ($walkIn ? "{$walkIn} (Walk-in)" : 'Unknown Customer');
        });

        $technician->jobs_completed = $technician->serviceRequests->where('status', 'completed')->count();
        $technician->jobs_pending = $technician->serviceRequests->where('status', 'pending')->count();
        $technician->average_rating = round(
            $technician->serviceRequests->whereNotNull('rating')->avg('rating') ?? 0,
            1
        );

        return view('staff.technicians.show', compact('technician'));
    }
}
