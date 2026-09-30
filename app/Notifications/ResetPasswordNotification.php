<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
  

    public function __construct(
        public string $token
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('🔐 إعادة تعيين كلمة المرور — متجر الملابس')
            ->greeting("مرحباً {$notifiable->full_name},")
            ->line('تلقّينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك.')
            ->line('اضغط على الزر أدناه لإنشاء كلمة مرور جديدة:')
            ->action('إعادة تعيين كلمة المرور', $url)
            ->line('⏱️ هذا الرابط صالح لمدة **60 دقيقة** فقط.')
            ->line('🔒 إذا لم تطلب هذا التغيير، يرجى تجاهل هذه الرسالة — حسابك آمن.')
            ->salutation('مع تحيات، فريق متجر الملابس');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'token' => $this->token,
            'type' => 'password_reset',
        ];
    }
}