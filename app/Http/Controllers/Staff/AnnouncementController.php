<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        // Staff can view their own announcements, announcements for staff, and general announcements
        $announcements = Announcement::with('creator')->where(function ($query) {
            $query->where('created_by', auth()->id())
                  ->orWhere('audience', 'staff')
                  ->orWhere('audience', 'all');
        })->latest()->paginate(10);
        return view('staff.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('staff.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience' => 'required|in:customers,technicians,staff,all',
            'priority' => 'required|in:normal,high',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'audience' => $request->audience,
            'priority' => $request->priority,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('announcements.index')->with('success', 'Announcement posted successfully.');
    }

    public function show(Announcement $announcement)
    {
        // Allow viewing own announcements, staff announcements, or general announcements
        if ($announcement->created_by !== Auth::id() && $announcement->audience !== 'all' && $announcement->audience !== 'staff') {
            return redirect()->back()->with('error', 'You can only view your own announcements, staff announcements, or general announcements.');
        }

        return view('staff.announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        // Allow only own announcements
        if ($announcement->created_by !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only edit your own announcements.');
        }

        return view('staff.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        if ($announcement->created_by !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'audience' => 'required|in:customers,technicians,staff,all',
            'priority' => 'required|in:normal,high',
        ]);

        $announcement->update($request->only('title', 'content', 'audience', 'priority'));

        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully.');
    }
}
