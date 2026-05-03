<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceRequest;
use App\Models\Announcement;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $staff = Auth::user();

        // Double check authorization
        if (!$staff || $staff->role !== 'staff') {
            abort(403, 'Unauthorized access for staff dashboard.');
        }

        try {
            // 🔹 Enhanced Summary Cards
            $totalRequests = ServiceRequest::count();
            $pendingRequests = ServiceRequest::where('status', 'pending')->count();
            $inProgressRequests = ServiceRequest::where('status', 'in-progress')->count();
            $completedRequests = ServiceRequest::where('status', 'completed')->count();
            $overdueRequests = 0; // Placeholder since due_date column doesn't exist

            // 🔹 Recent Service Requests (10 most recent)
            $recentRequests = ServiceRequest::with(['customer.user', 'technician'])
                ->latest()
                ->take(10)
                ->get();

            // 🔹 Recent Activities (last 10 actions)
            $recentActivities = ServiceRequest::with(['customer.user'])
                ->latest('updated_at')
                ->take(10)
                ->get()
                ->map(function ($request) {
                    $customerName = $request->customer && $request->customer->user ? $request->customer->user->name : 'Unknown';
                    return [
                        'type' => 'service_request',
                        'action' => ucfirst($request->status),
                        'description' => "Service request #{$request->id} for {$customerName}",
                        'time' => $request->updated_at->diffForHumans(),
                        'icon' => match($request->status) {
                            'pending' => 'clock',
                            'assigned' => 'user-check',
                            'in-progress' => 'cog',
                            'completed' => 'check-circle',
                            default => 'circle'
                        },
                        'color' => match($request->status) {
                            'pending' => 'yellow',
                            'assigned' => 'blue',
                            'in-progress' => 'orange',
                            'completed' => 'green',
                            default => 'gray'
                        }
                    ];
                });

            // 🔹 Active Announcements for staff
            $announcements = Announcement::where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->where(function ($q) {
                    $q->where('audience', 'staff')
                      ->orWhere('audience', 'all');
                })
                ->orderBy('priority', 'desc')
                ->latest()
                ->take(5)
                ->get();

            // 🔹 Enhanced Monthly Chart Data
            $monthlyData = ServiceRequest::whereYear('created_at', now()->year)
                ->get()
                ->groupBy(fn($r) => $r->created_at->format('M'))
                ->map(function($month) {
                    return [
                        'total' => $month->count(),
                        'completed' => $month->where('status', 'completed')->count(),
                        'pending' => $month->where('status', 'pending')->count(),
                        'in_progress' => $month->where('status', 'in-progress')->count(),
                    ];
                })
                ->toArray();

            $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $monthlyCompleted = collect($months)->mapWithKeys(fn($m) => [$m => $monthlyData[$m]['completed'] ?? 0])->toArray();
            $monthlyTotal = collect($months)->mapWithKeys(fn($m) => [$m => $monthlyData[$m]['total'] ?? 0])->toArray();
            $monthlyPending = collect($months)->mapWithKeys(fn($m) => [$m => $monthlyData[$m]['pending'] ?? 0])->toArray();

            // 🔹 Performance Metrics
            $completionRate = $totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100, 1) : 0;
            $avgCompletionTime = ServiceRequest::where('status', 'completed')
                ->whereNotNull('completed_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_hours')
                ->first()
                ->avg_hours ?? 0;

            // 🔹 Quick Stats for Today
            $todayRequests = ServiceRequest::whereDate('created_at', today())->count();
            $todayCompleted = ServiceRequest::where('status', 'completed')
                ->whereDate('updated_at', today())
                ->count();

            return view('staff.dashboard', compact(
                'totalRequests',
                'pendingRequests',
                'inProgressRequests',
                'completedRequests',
                'overdueRequests',
                'recentRequests',
                'recentActivities',
                'announcements',
                'monthlyCompleted',
                'monthlyTotal',
                'monthlyPending',
                'completionRate',
                'avgCompletionTime',
                'todayRequests',
                'todayCompleted'
            ));

        } catch (\Exception $e) {
            // Log the error and show a friendly message
            \Log::error('Staff Dashboard Error: ' . $e->getMessage());
            
            return view('staff.dashboard')->with('error', 'Unable to load dashboard data. Please try again.');
        }
    }
}