<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Technician;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StaffReportController extends Controller
{
    /**
     * Display the reports dashboard (Blade view)
     */
    public function index()
    {
        return view('staff.reports.index');
    }

    /**
     * Generate print-friendly report
     */
    public function print(Request $request)
    {
        $from = $request->input('from') ? Carbon::parse($request->input('from'))->startOfDay() : now()->subDays(30)->startOfDay();
        $to   = $request->input('to') ? Carbon::parse($request->input('to'))->endOfDay() : now()->endOfDay();

        $data = $this->getReportData($from, $to);
        $data['from'] = $from->format('Y-m-d');
        $data['to'] = $to->format('Y-m-d');

        return view('reports.print', $data);
    }

    /**
     * Generate PDF report
     */
    public function pdf(Request $request)
    {
        $from = $request->input('from') ? Carbon::parse($request->input('from'))->startOfDay() : now()->subDays(30)->startOfDay();
        $to   = $request->input('to') ? Carbon::parse($request->input('to'))->endOfDay() : now()->endOfDay();

        $data = $this->getReportData($from, $to);
        $data['from'] = $from->format('Y-m-d');
        $data['to'] = $to->format('Y-m-d');

        $pdf = Pdf::loadView('reports.pdf', $data);
        return $pdf->download('reports_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * API endpoint for fetching report summary
     */
    public function summary(Request $request)
    {
        // 🔧 Filter range
        $from = $request->input('from') ? Carbon::parse($request->input('from'))->startOfDay() : now()->subDays(30)->startOfDay();
        $to   = $request->input('to') ? Carbon::parse($request->input('to'))->endOfDay() : now()->endOfDay();

        // Check if comparison is requested
        $compare = $request->boolean('comparison', false);

        $data = $this->getReportData($from, $to, $compare);

        return response()->json([
            'summary' => $data['summary'],
            'jobs_per_day' => $data['jobs_per_day'],
            'job_types'    => $data['job_types'],
            'technicians'  => $data['technicians'],
            'revenues'     => $data['revenues'],
            'recent_jobs'  => $data['recent_jobs'],
            'comparison'   => $data['comparison'] ?? null,
            'current_period' => $data['current_period'] ?? null,
            'revenues_comparison' => $data['revenues_comparison'] ?? null,
        ]);
    }

    /**
     * Helper method to get all report data
     */
    private function getReportData($from, $to)
    {
        /*
        |--------------------------------------------------------------------------
        | 1️⃣ SERVICE REQUESTS KPIs
        |--------------------------------------------------------------------------
        */
        $requests = ServiceRequest::whereBetween('created_at', [$from, $to])->get();

        $totalJobs     = $requests->count();
        $completedJobs = $requests->where('status', 'completed')->count();
        $pendingJobs   = $requests->whereIn('status', ['pending', 'assigned', 'in-progress'])->count();

        // Average completion time in hours
        $avgCompletion = $requests->filter(fn($r) => $r->assigned_at && $r->completed_at)
            ->map(fn($r) => Carbon::parse($r->assigned_at)->diffInHours(Carbon::parse($r->completed_at)))
            ->avg() ?? 0;

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ JOBS PER DAY (Chart)
        |--------------------------------------------------------------------------
        */
        $jobsPerDay = ServiceRequest::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ JOB TYPES DISTRIBUTION (Chart)
        |--------------------------------------------------------------------------
        */
        $jobTypes = ServiceRequest::select('service_type', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('service_type')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 4️⃣ TECHNICIAN PERFORMANCE
        |--------------------------------------------------------------------------
        */
        $technicians = Technician::with(['serviceRequests' => function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            }])
            ->get()
            ->map(function ($tech) {
                $completed = $tech->serviceRequests->where('status', 'completed');
                $avgHours = $completed->filter(fn($r) => $r->assigned_at && $r->completed_at)
                    ->map(fn($r) => Carbon::parse($r->assigned_at)->diffInHours(Carbon::parse($r->completed_at)))
                    ->avg() ?? 0;

                return [
                    'name'       => $tech->full_name ?? 'Unnamed Technician',
                    'completed'  => $completed->count(),
                    'avg_hrs'    => round($avgHours, 1),
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | 5️⃣ REVENUE REPORT (Invoice Table + Chart)
        |--------------------------------------------------------------------------
        */
        $revenues = Invoice::select(
                DB::raw('DATE(billing_date) as date'),
                DB::raw('SUM(amount_due) as total')
            )
            ->whereBetween('billing_date', [$from, $to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 6️⃣ RECENT JOBS TABLE
        |--------------------------------------------------------------------------
        */
        $recentJobs = ServiceRequest::with(['technician'])
            ->whereBetween('created_at', [$from, $to])
            ->latest('created_at')
            ->take(20)
            ->get()
            ->map(function ($r) {
                return [
                    'id'          => $r->id,
                    'customer'    => $r->display_customer_name,
                    'technician'  => $r->technician->full_name ?? 'Unassigned',
                    'type'        => $r->service_type ?? 'N/A',
                    'status'      => ucfirst($r->status),
                    'assigned_at' => optional($r->assigned_at)->toDateTimeString(),
                    'completed_at'=> optional($r->completed_at)->toDateTimeString(),
                ];
            });

        return [
            'summary' => [
                'total_jobs'      => $totalJobs,
                'completed'       => $completedJobs,
                'pending'         => $pendingJobs,
                'avg_completion'  => round($avgCompletion, 1),
            ],
            'jobs_per_day' => $jobsPerDay,
            'job_types'    => $jobTypes,
            'technicians'  => $technicians,
            'revenues'     => $revenues,
            'recent_jobs'  => $recentJobs,
        ];
    }
}
