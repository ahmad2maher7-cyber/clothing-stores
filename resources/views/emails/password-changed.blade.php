<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تغيير كلمة المرور</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f5f5f4; padding: 40px; direction: rtl;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">

        {{-- Header --}}
        <div style="background: linear-gradient(135deg, #166534 0%, #14b8a6 100%); padding: 30px; text-align: center;">
            <h1 style="color: white; margin: 0; font-size: 24px;">🔐 تنبيه أمني</h1>
        </div>

        {{-- Body --}}
        <div style="padding: 30px;">
            <p style="color: #333; font-size: 16px; margin-bottom: 20px;">
                مرحباً <strong>{{ $userName }}</strong>,
            </p>

            <p style="color: #555; line-height: 1.6; margin-bottom: 25px;">
                تم تغيير كلمة المرور الخاصة بحسابك بنجاح.
            </p>

            {{-- Details --}}
            <div style="background: #f9fafb; border-right: 4px solid #166534; padding: 20px; margin-bottom: 25px;">
                <table style="width: 100%; font-size: 14px;">
                    <tr>
                        <td style="padding: 8px 0; color: #666;">🕐 الوقت:</td>
                        <td style="padding: 8px 0; color: #333; font-weight: bold;">{{ $changedAt }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666;">🌐 عنوان IP:</td>
                        <td style="padding: 8px 0; color: #333; font-weight: bold; direction: ltr;">{{ $ipAddress }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 0; color: #666;">💻 الجهاز:</td>
                        <td style="padding: 8px 0; color: #333; font-size: 12px;">{{ \Illuminate\Support\Str::limit($userAgent, 100) }}</td>
                    </tr>
                </table>
            </div>

            {{-- Warning --}}
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; margin-bottom: 25px;">
                <p style="color: #991b1b; font-weight: bold; margin: 0 0 8px 0;">
                    ⚠️ إذا لم تقم بهذا التغيير:
                </p>
                <ul style="color: #7f1d1d; margin: 0; padding-right: 20px; font-size: 14px;">
                    <li style="margin-bottom: 5px;">تواصل معنا فوراً</li>
                    <li>قم بتغيير كلمة المرور مرة أخرى</li>
                </ul>
            </div>

            <p style="color: #555; line-height: 1.6; margin-bottom: 25px;">
                إذا كنت أنت من قام بهذا التغيير، يمكنك تجاهل هذه الرسالة بأمان.
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/login') }}" 
                   style="display: inline-block; background: #166534; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px; font-weight: bold;">
                    تسجيل الدخول
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <div style="background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #666;">
            <p style="margin: 0;">مع تحيات، فريق الأمان — متجر الملابس</p>
            <p style="margin: 5px 0 0 0;">© {{ date('Y') }} جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>