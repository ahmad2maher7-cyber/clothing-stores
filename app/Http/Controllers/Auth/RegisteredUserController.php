<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function __construct(
        protected EmailVerificationService $verificationService
    ) {}

    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        // إنشاء المستخدم
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => 'active',
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // تسجيل الدخول
        Auth::login($user);

        // توليد وإرسال كود OTP
        $result = $this->verificationService->generateAndSend($user);

        if (!$result['success']) {
            return redirect()->route('verification.notice')
                ->with('error', $result['message']);
        }

        // في بيئة التطوير: نحفظ الكود في الجلسة للمساعدة في الاختبار
        if (app()->isLocal()) {
            session()->flash('dev_code', $result['code']);
        }

        return redirect()->route('verification.notice')
            ->with('success', 'تم إرسال كود التحقق إلى بريدك الإلكتروني');
    }
}