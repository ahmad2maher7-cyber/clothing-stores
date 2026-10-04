<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * عرض آخر 10 إشعارات (AJAX - للقائمة المنسدلة)
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'body' => $n->body,
                'type' => $n->type,
                'is_read' => (bool) $n->is_read,
                'time' => $n->created_at->diffForHumans(),
                'url' => $this->getNotificationUrl($n),
            ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => auth()->user()->notifications()->where('is_read', false)->count(),
        ]);
    }

    /**
     * صفحة كل الإشعارات
     */
    public function all()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * عدد غير المقروءة
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->notifications()->where('is_read', false)->count(),
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
        auth()->user()->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * توليد رابط الإشعار
     */
    protected function getNotificationUrl(Notification $notification): string
    {
        return match ($notification->type) {
            'order_created', 'order_status_changed' => route('customer.orders.index'),
            'order_placed' => route('customer.orders.index'),
            'product_created' => auth()->user()->role === 'admin'
                ? route('admin.stores.index')
                : route('merchant.products.index'),
            'review_created' => route('merchant.reviews.index'),
            'review_approved' => route('customer.reviews.index'),
            'offer_created' => route('offers.index'),
            default => route('dashboard'),
        };
    }
}