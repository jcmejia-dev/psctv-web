<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Events\NewAnnouncementCreated;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'audience'   => 'required|in:all,customers,technicians,staff',
            'priority'   => 'required|in:normal,high',
            'start_date' => 'nullable|date_format:Y-m-d\TH:i',
            'end_date'   => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:start_date',
        ]);

        // Convert datetime-local format to proper datetime
        if (!empty($validated['start_date'])) {
            $validated['start_date'] = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $validated['start_date']);
        } else {
            $validated['start_date'] = null;
        }
        if (!empty($validated['end_date'])) {
            $validated['end_date'] = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $validated['end_date']);
        } else {
            $validated['end_date'] = null;
        }

        // Create the announcement
        $announcement = Announcement::create($validated);

        // Select users based on audience
        $recipients = match ($validated['audience']) {
            'customers'   => User::where('role', 'customer')->get(),
            'technicians' => User::where('role', 'technician')->get(),
            'staff'       => User::where('role', 'staff')->get(),
            default       => User::whereIn('role', ['customer', 'technician', 'staff', 'admin'])->get(),
        };

        // Send database notifications
        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new NewAnnouncementNotification($announcement));
        }

        // Broadcast real-time event to Pusher
        event(new NewAnnouncementCreated($announcement));

        return redirect()->route('announcements.index')
            ->with('success', '📢 Announcement created and notifications sent successfully.');
    }

    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'audience'   => 'required|in:all,customers,technicians,staff',
            'priority'   => 'required|in:normal,high',
            'start_date' => 'nullable|date_format:Y-m-d\TH:i',
            'end_date'   => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:start_date',
        ]);

        // Convert datetime-local format to proper datetime
        if (!empty($validated['start_date'])) {
            $validated['start_date'] = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $validated['start_date']);
        } else {
            $validated['start_date'] = null;
        }
        if (!empty($validated['end_date'])) {
            $validated['end_date'] = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $validated['end_date']);
        } else {
            $validated['end_date'] = null;
        }

        $announcement->update($validated);

        return redirect()->route('announcements.index')
            ->with('success', '✅ Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', '🗑️ Announcement deleted successfully.');
    }

    /**
     * API endpoint for technician announcements - FIXED VERSION
     */
    public function apiIndex(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                Log::warning('Announcements API: Unauthorized access attempt');
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access',
                    'announcements' => []
                ], 401);
            }

            Log::info("Announcements API: Fetching announcements for user {$user->id} with role {$user->role}");

            // Get announcements with proper filtering
            $announcements = Announcement::query()
                ->where(function ($q) use ($user) {
                    $q->where('audience', 'all')
                      ->orWhere('audience', $user->role);
                })
                ->where(function ($q) {
                    $q->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
                })
                ->orderByDesc('created_at')
                ->get(['id', 'title', 'content', 'priority', 'audience', 'start_date', 'end_date', 'created_at']);

            Log::info("Announcements API: Found {$announcements->count()} announcements");

            // Transform the data to match frontend expectations
            $formattedAnnouncements = $announcements->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title ?: 'No Title',
                    'content' => $announcement->content ?: 'No content available',
                    'message' => $announcement->content ?: 'No content available', // Add message field as alias
                    'priority' => $announcement->priority ?: 'normal',
                    'audience' => $announcement->audience ?: 'all',
                    'created_at' => $announcement->created_at ? $announcement->created_at->toISOString() : now()->toISOString(),
                    'start_date' => $announcement->start_date ? $announcement->start_date->toISOString() : null,
                    'end_date' => $announcement->end_date ? $announcement->end_date->toISOString() : null,
                ];
            });

            $response = [
                'success' => true,
                'message' => 'Announcements retrieved successfully',
                'announcements' => $formattedAnnouncements,
                'count' => $formattedAnnouncements->count(),
                'user_role' => $user->role // For debugging
            ];

            Log::info("Announcements API: Sending response with {$formattedAnnouncements->count()} announcements");

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Announcements API Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch announcements',
                'announcements' => [],
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Test endpoint to check if announcements API is working
     */
    public function apiTest(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No authenticated user'
                ], 401);
            }

            $totalAnnouncements = Announcement::count();
            $userAnnouncements = Announcement::where('audience', 'all')
                ->orWhere('audience', $user->role)
                ->count();

            return response()->json([
                'success' => true,
                'message' => 'Announcements API is working',
                'data' => [
                    'user_id' => $user->id,
                    'user_role' => $user->role,
                    'total_announcements' => $totalAnnouncements,
                    'user_eligible_announcements' => $userAnnouncements,
                    'timestamp' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'API test failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}