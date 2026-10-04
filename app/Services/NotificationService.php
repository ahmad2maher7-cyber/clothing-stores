<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * إرسال إشعار لمستخدم واحد
     */
    public static function send(
        User $user,
        string $type,
        string $title,
        string $body,
        ?string $actionUrl = null
    ): Notification {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'is_read' => false,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * إرسال إشعار لعدة مستخدمين
     */
    public static function sendMany(
        array $userIds,
        string $type,
        string $title,
        string $body,
        ?string $actionUrl = null
    ): void {
        $now = now();
        $records = array_map(fn($userId) => [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'is_read' => false,
            'action_url' => $actionUrl,
            'created_at' => $now,
            'updated_at' => $now,
        ], $userIds);

        Notification::insert($records);
    }

    /**
     * إشعار: طلب جديد (للتاجر)
     */
    public static function orderCreated($order): void
    {
        $store = $order->store;

        self::send(
            $store->merchant,
            'order',
            '🛒 طلب جديد #' . $order->order_number,
            'وصلك طلب جديد بقيمة ' . number_format($order->total, 0) . ' ₪',
            route('merchant.orders.show', $order)
        );
    }

    /**
     * إشعار: تغيير حالة الطلب (للزبون)
     */
    public static function orderStatusChanged($order, string $newStatus): void
    {
        $statusLabels = [
            'processing' => '⚙️ قيد التجهيز',
            'shipped' => '🚚 تم الشحن',
            'delivering' => '📍 قيد التوصيل',
            'delivered' => '✅ تم التسليم',
            'cancelled' => '❌ ملغى',
            'returned' => '↩️ مرتجع',
        ];

        $label = $statusLabels[$newStatus] ?? $newStatus;

        self::send(
            $order->customer,
            'order',
            '📦 تحديث طلب #' . $order->order_number,
            'حالة طلبك الآن: ' . $label,
            route('customer.orders.show', $order)
        );
    }

    /**
     * إشعار: اعتماد التقييم (للزبون)
     */
    public static function reviewApproved($review): void
    {
        self::send(
            $review->customer,
            'review',
            '⭐ تم اعتماد تقييمك',
            'شكراً! تم نشر تقييمك على ' . $review->product->name,
            route('products.show', $review->product->slug)
        );
    }

    /**
     * إشعار: اعتماد المتجر (للتاجر)
     */
    public static function storeApproved($store): void
    {
        self::send(
            $store->merchant,
            'store',
            '🎉 تم اعتماد متجرك!',
            'مبروك! متجرك "' . $store->name . '" أصبح نشطاً الآن',
            route('merchant.dashboard')
        );
    }

    /**
     * إشعار: رفض/إيقاف المتجر (للتاجر)
     */
    public static function storeSuspended($store): void
    {
        self::send(
            $store->merchant,
            'store',
            '⛔ تم إيقاف متجرك',
            'متجرك "' . $store->name . '" تم إيقافه. للاستفسار تواصل مع الدعم',
            route('merchant.store.pending')
        );
    }

    /**
     * إشعار: ترحيب بعد التسجيل
     */
    public static function welcome(User $user): void
    {
        self::send(
            $user,
            'welcome',
            '🎉 مرحباً بك في متجر الملابس!',
            'شكراً لتسجيلك. ابدأ التسوق الآن واستفد من عروضنا',
            route('home')
        );
    }

    /**
     * إشعار: مخزون منخفض (للتاجر)
     */
    public static function lowStock($variant): void
    {
        $product = $variant->product;
        $store = $product->store;

        self::send(
            $store->merchant,
            'stock',
            '⚠️ مخزون منخفض',
            'المنتج "' . $product->name . '" (مقاس: ' . $variant->size . '، لون: ' . $variant->color . ') - الكمية: ' . $variant->stock_quantity,
            route('merchant.inventory.lowStock')
        );
    }
}