<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * جلب الإشعارات (AJAX)
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->take(15)
            ->get();

        $unreadCount = auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * تعليم إشعار واحد كمقروء
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * تعليم الكل كمقروء
     */
    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * عدد الإشعارات غير المقروءة فقط (للعداد)
     */
    public function unreadCount()
    {
        $count = auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * صفحة كل الإشعارات
     */
    public function all()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }
}
