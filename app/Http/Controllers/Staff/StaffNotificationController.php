<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffNotificationController extends Controller
{
    public function index()
    {
        $staff = Auth::user();
        $notifications = $staff->notifications()->latest()->paginate(10);
        return view('staff.notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        $staff = Auth::user();
        $staff->unreadNotifications->each->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function markAsRead($id)
    {
        $staff = Auth::user();
        $notification = $staff->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('staff.notifications.index');
        return redirect($url);
    }
}
