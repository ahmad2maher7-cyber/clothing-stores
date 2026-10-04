<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * صفحة كل الإشعارات
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * آخر 5 إشعارات (للـ Dropdown)
     */
    public function latest()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->take(5)
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
     * عدد الإشعارات غير المقروءة فقط (Badge)
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
     * تعليم إشعار كمقروء
     */
    public function markAsRead(Notification $notification)
    {
        // تحقق الملكية
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        // إذا كان الطلب AJAX
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /**
     * تعليم كل الإشعارات كمقروءة
     */
    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', '✅ تم تعليم جميع الإشعارات كمقروءة');
    }

    /**
     * حذف إشعار
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'تم حذف الإشعار');
    }
}