<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'avatar',
        'failed_attempts',      // ← أضف
    'locked_until', 
    'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'locked_until' => 'datetime', 
        ];
        
    }

    // ============ العلاقات ============

    // التاجر يملك متاجر
    public function stores()
    {
        return $this->hasMany(Store::class, 'merchant_id');
    }

    // الزبون لديه طلبات
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    // الزبون لديه سلات
    public function carts()
    {
        return $this->hasMany(Cart::class, 'customer_id');
    }

    // التقييمات
    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    // المفضلة
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'customer_id');
    }

    // الإشعارات
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // رسائل التواصل
    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class, 'customer_id');
    }

    // رسائل الشات
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    // المحادثات
    public function chatConversations()
    {
        return $this->hasMany(ChatConversation::class, 'customer_id');
    }

    // سجل النشاطات
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // حركات المخزون
    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class, 'created_by');
    }

    // ============ Helper Methods ============

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMerchant(): bool
    {
        return $this->role === 'merchant';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
    // ============ Security Relations ============
public function emailVerificationCodes()
{
    return $this->hasMany(EmailVerificationCode::class);
}

public function failedLoginAttempts()
{
    return $this->hasMany(FailedLoginAttempt::class);
}

// ============ Security Helper Methods ============
public function isLocked(): bool
{
    return $this->locked_until && $this->locked_until->isFuture();
}

public function incrementFailedAttempts(): void
{
    $this->increment('failed_attempts');

    // إذا وصل إلى 3 محاولات → احظر 15 دقيقة
    if ($this->failed_attempts >= 3) {
        $this->update(['locked_until' => now()->addMinutes(15)]);
    }
}

public function resetFailedAttempts(): void
{
    $this->update([
        'failed_attempts' => 0,
        'locked_until' => null,
    ]);
}

/**
 * Override: استخدام Notification مخصص لإعادة تعيين كلمة المرور
 */
public function sendPasswordResetNotification($token): void
{
    $this->notify(new \App\Notifications\ResetPasswordNotification($token));
}
}