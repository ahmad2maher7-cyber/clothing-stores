<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    public function __construct(
        public string $ipAddress,
        public string $userAgent,
        public string $changedAt
    ) {}

    /**
     * قنوات الإرسال - mail فقط (بدون queue وبدون database)
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * محتوى الإيميل
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔐 تنبيه أمني: تم تغيير كلمة المرور')
            ->greeting("مرحباً {$notifiable->full_name},")
            ->line('تم تغيير كلمة المرور الخاصة بحسابك بنجاح.')
            ->line("🕐 التاريخ: {$this->changedAt}")
            ->line("🌐 IP: {$this->ipAddress}")
            ->line('إذا لم تقم بهذا الإجراء، يرجى التواصل معنا فوراً لتأمين حسابك.')
            ->salutation('مع تحيات، فريق متجر الملابس');
    }

    /**
     * محتوى الإشعار (للاستخدام المستقبلي مع database channel)
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'password_changed',
            'title' => '🔐 تم تغيير كلمة المرور',
            'body' => 'تم تغيير كلمة مرور حسابك بنجاح',
            'ip_address' => $this->ipAddress,
            'changed_at' => $this->changedAt,
        ];
    }
}