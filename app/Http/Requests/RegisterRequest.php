<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email:rfc,dns',   // ← ← ← التحقق من الصيغة + DNS
                'max:255',
                'unique:users,email',
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:customer,merchant'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'الاسم الكامل مطلوب',
            'full_name.max' => 'الاسم طويل جداً',

            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب إدخال بريد إلكتروني بصيغة صحيحة (مثل example@gmail.com)',
            'email.unique' => 'هذا البريد مستخدم بالفعل',
            'email.max' => 'البريد الإلكتروني طويل جداً',
            'email.lowercase' => 'يجب أن يكون البريد بأحرف صغيرة',

            'phone.max' => 'رقم الهاتف طويل جداً',

            'role.required' => 'نوع الحساب مطلوب',
            'role.in' => 'نوع الحساب غير صحيح',

            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
        ];
    }

    /**
     * تحضير البيانات قبل التحقق
     */
    protected function prepareForValidation(): void
    {
        if ($this->email) {
            $this->merge([
                'email' => strtolower(trim($this->email)),
            ]);
        }
    }
}