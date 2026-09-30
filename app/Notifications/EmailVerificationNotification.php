<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationNotification extends Notification
{
    // ❌ حذفنا Queueable لضمان الإرسال الفوري

    public function __construct(
        public string $code
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔐 كود التحقق من بريدك الإلكتروني')
            ->greeting("مرحباً {$notifiable->full_name},")
            ->line('شكراً لتسجيلك معنا!')
            ->line('استخدم الكود التالي لتفعيل حسابك:')
            ->line("**{$this->code}**")
            ->line('هذا الكود صالح لمدة 10 دقائق فقط.')
            ->line('إذا لم تطلب هذا الكود، يرجى تجاهل هذه الرسالة.')
            ->salutation('مع تحيات، متجر الملابس');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'code' => $this->code,
        ];
    }
}