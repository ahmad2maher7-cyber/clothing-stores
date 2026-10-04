<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userName,
        public string $ipAddress,
        public string $userAgent,
        public string $loginTime
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔐 تسجيل دخول جديد إلى حسابك - متجر الملابس',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.login-notification',
        );
    }
}