<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $ipAddress,
        public string $userAgent,
        public string $loginTime
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];   // ← إيميل + قاعدة بيانات
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔐 تسجيل دخول جديد إلى حسابك')
            ->greeting("مرحباً {$notifiable->full_name},")
            ->line('تم تسجيل الدخول إلى حسابك بنجاح.')
            ->line('**تفاصيل الدخول:**')
            ->line("🕐 الوقت: {$this->loginTime}")
            ->line("🌐 IP: {$this->ipAddress}")
            ->line('💻 المتصفح: ' . \Illuminate\Support\Str::limit($this->userAgent, 80))
            ->line('إذا لم تكن أنت، يرجى تغيير كلمة المرور فوراً.')
            ->action('تغيير كلمة المرور', url('/profile'))
            ->salutation('مع تحيات، متجر الملابس');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'login',
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'login_time' => $this->loginTime,
            'message' => 'تم تسجيل دخول جديد إلى حسابك',
        ];
    }
}