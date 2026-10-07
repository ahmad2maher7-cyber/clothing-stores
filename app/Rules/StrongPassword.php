<?php

namespace App\Rules;

use Illuminate\Validation\Rules\Password;

class StrongPassword
{
    /**
     * قواعد كلمة المرور القوية
     */
    public static function rules(): Password
    {
        return Password::min(10)
            ->letters()        // يجب أن تحتوي على أحرف
            ->mixedCase()      // حرف كبير + حرف صغير
            ->numbers()        // رقم واحد على الأقل
            ->symbols()        // رمز خاص (@, #, $, !)
            ->uncompromised(); // ليست مسرّبة في قواعد بيانات
    }

    /**
     * رسائل خطأ بالعربية
     */
    public static function messages(): array
    {
        return [
            'password.min' => '⚠️ كلمة المرور يجب أن تكون 10 أحرف على الأقل',
            'password.letters' => '⚠️ يجب أن تحتوي على أحرف',
            'password.mixed' => '⚠️ يجب أن تحتوي على حرف كبير وحرف صغير',
            'password.numbers' => '⚠️ يجب أن تحتوي على رقم واحد على الأقل',
            'password.symbols' => '⚠️ يجب أن تحتوي على رمز خاص (@, #, $, !...)',
            'password.uncompromised' => '⚠️ كلمة المرور هذه مسرّبة — اختر كلمة أخرى',
        ];
    }

    /**
     * الحصول على قواعد كلمة المرور للحالات المختلفة
     */
    public static function strong(): Password
    {
        return self::rules();
    }
}
