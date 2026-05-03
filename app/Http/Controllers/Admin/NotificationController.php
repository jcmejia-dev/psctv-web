<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // ✅ List all notifications for the current logged-in user
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }

        $notifications = $user->notifications()->latest()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    // ✅ Mark all unread notifications as read
    public function markAllRead(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }

        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    // ✅ Mark single notification as read and stay on notifications page
    public function markAsRead($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }

        try {
            $notification = $user->notifications()->findOrFail($id);
            $notification->markAsRead();

            return back()->with('success', 'Notification marked as read.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('notifications.index')->with('error', 'Notification not found.');
        }
    }

    // ✅ Alternative method name if you prefer "markRead"
    public function markRead($id)
    {
        return $this->markAsRead($id);
    }

    // ✅ Read notification and redirect to appropriate page
    public function read($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }

        try {
            $notification = $user->notifications()->findOrFail($id);
            $notification->markAsRead();

            // Redirect based on notification type
            $type = class_basename($notification->type);
            $dataType = $notification->data['type'] ?? null;

            switch ($type) {
                case 'NewAnnouncementNotification':
                    return redirect()->route('announcements.index')->with('success', 'Notification marked as read.');

                case 'TechnicianAssigned':
                case 'ServiceCompleted':
                    if (!empty($notification->data['service_request_id'])) {
                        return redirect()->route('service_requests.show', $notification->data['service_request_id'])
                            ->with('success', 'Notification marked as read.');
                    }
                    break;

                case 'OverduePaymentNotification':
                    if (!empty($notification->data['subscription_id'])) {
                        return redirect()->route('staff.subscriptions.show', $notification->data['subscription_id'])
                            ->with('success', 'Notification marked as read.');
                    }
                    break;

                default:
                    // Check for data-based type (for database notifications)
                    if ($dataType === 'overdue_payment' && !empty($notification->data['subscription_id'])) {
                        return redirect()->route('staff.subscriptions.show', $notification->data['subscription_id'])
                            ->with('success', 'Notification marked as read.');
                    }
                    return redirect()->route('notifications.index')->with('success', 'Notification marked as read.');
            }

            return redirect()->route('notifications.index')->with('success', 'Notification marked as read.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('notifications.index')->with('error', 'Notification not found.');
        }
    }
}