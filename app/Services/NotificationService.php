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
        int $userId,
        string $title,
        string $body,
        string $type = 'general'
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'is_read' => false,
        ]);
    }

    /**
     * إرسال لأدوار محددة
     */
    public static function sendToRole(
        string $role,
        string $title,
        string $body,
        string $type = 'general'
    ): int {
        $users = User::where('role', $role)->get();
        $count = 0;

        foreach ($users as $user) {
            self::send($user->id, $title, $body, $type);
            $count++;
        }

        return $count;
    }

    /**
     * إرسال للمشرفين
     */
    public static function sendToAdmins(
        string $title,
        string $body,
        string $type = 'general'
    ): int {
        return self::sendToRole('admin', $title, $body, $type);
    }

    /**
     * عدد الإشعارات غير المقروءة
     */
    public static function unreadCount(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * تعليم الكل كمقروء
     */
    public static function markAllAsRead(int $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}