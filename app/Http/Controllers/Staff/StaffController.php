<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceRequest;
use App\Models\Announcement;

class DashboardController extends Controller
{
    public function index()
    {
        $staff = Auth::user();

        if (!$staff || $staff->role !== 'staff') {
            abort(403, 'Unauthorized');
        }

        // 🔹 Summary Cards (show all requests)
        $totalRequests = ServiceRequest::count();
        $inProgressRequests = ServiceRequest::where('status','in-progress')->count();
        $completedRequests = ServiceRequest::where('status','completed')->count();

        // 🔹 Requests Table (show all requests)
        $assignedRequests = ServiceRequest::with(['customer.user'])
            ->latest()
            ->take(10)
            ->get();

        // 🔹 Active Announcements Feed
        $announcements = Announcement::active()
            ->where('audience','staff')
            ->orderBy('priority','desc')
            ->latest()
            ->take(5)
            ->get();

        // 🔹 Monthly Completed Requests Chart Data
        $monthlyCompleted = ServiceRequest::where('status','completed')
            ->whereYear('created_at', now()->year)
            ->get()
            ->groupBy(fn($r) => $r->created_at->format('M'))
            ->map(fn($month) => $month->count())
            ->toArray();

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $monthlyCompleted = collect($months)->mapWithKeys(fn($m) => [$m => $monthlyCompleted[$m] ?? 0])->toArray();

        return view('staff.dashboard', compact(
            'totalRequests',
            'inProgressRequests',
            'completedRequests',
            'assignedRequests',
            'announcements',
            'monthlyCompleted'
        ));
    }
}
