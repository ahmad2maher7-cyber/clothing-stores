<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $ipAddress,
        public string $userAgent,
        public string $changedAt
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔐 تنبيه أمني: تم تغيير كلمة المرور')
            ->greeting("مرحباً {$notifiable->full_name},")
            ->line('تم **تغيير كلمة المرور** الخاصة بحسابك بنجاح.')
            ->line('**تفاصيل التغيير:**')
            ->line("🕐 التاريخ: {$this->changedAt}")
            ->line("🌐 عنوان IP: {$this->ipAddress}")
            ->line("💻 الجهاز: " . \Illuminate\Support\Str::limit($this->userAgent, 80))
            ->line('**⚠️ إذا لم تقم بهذا التغيير:**')
            ->line('• تواصل معنا فوراً')
            ->line('• قم بتغيير كلمة المرور مرة أخرى')
            ->salutation('مع تحيات، فريق الأمان — متجر الملابس');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'password_changed',
            'title' => '🔐 تم تغيير كلمة المرور',
            'body' => 'تم تغيير كلمة مرور حسابك بنجاح',
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'changed_at' => $this->changedAt,
        ];
    }
}