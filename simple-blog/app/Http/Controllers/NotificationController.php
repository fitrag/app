<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->with(['actor', 'notifiable'])
            ->latest()
            ->paginate(20);

        // Mark all as read when viewing the page
        Auth::user()->notifications()->unread()->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'redirect' => $notification->link
        ]);
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->unread()->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function unreadCount()
    {
        $count = Auth::user()->notifications()->unread()->count();

        return response()->json(['count' => $count]);
    }

    public function recent()
    {
        $notifications = Auth::user()
            ->notifications()
            ->with(['actor', 'notifiable'])
            ->recent(10)
            ->get();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'actor_name' => $notification->actor->name,
                    'time_ago' => $notification->created_at->diffForHumans(),
                    'is_read' => $notification->read_at !== null,
                ];
            }),
            'unread_count' => Auth::user()->notifications()->unread()->count()
        ]);
    }
}
