<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordChangedNotification extends Notification
{
    // ❌ حذفنا Queueable
    // use Queueable;

    public function __construct(
        public string $ipAddress,
        public string $userAgent,
        public string $changedAt
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔔 تنبيه أمني: تم تغيير كلمة المرور')
            ->greeting("مرحباً {$notifiable->full_name},")
            ->line('تم **تغيير كلمة المرور** الخاصة بحسابك بنجاح.')
            ->line('**تفاصيل التغيير:**')
            ->line("🕐 التاريخ: {$this->changedAt}")
            ->line("🌐 عنوان IP: {$this->ipAddress}")
            ->line("💻 الجهاز: " . substr($this->userAgent, 0, 80))
            ->line('**⚠️ إذا لم تقم بهذا التغيير:**')
            ->line('• تواصل معنا فوراً')
            ->line('• قم بتغيير كلمة المرور مرة أخرى')
            ->salutation('مع تحيات، فريق الأمان — متجر الملابس');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'changed_at' => $this->changedAt,
            'type' => 'password_changed',
        ];
    }
}