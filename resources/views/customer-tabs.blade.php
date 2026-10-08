<?php

namespace App\Http\Requests\Auth;

use App\Rules\StrongPassword;
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
            'full_name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'role' => ['required', 'in:customer,merchant'],
            'password' => ['required', 'confirmed', StrongPassword::rules()],
            'terms' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return array_merge(
            StrongPassword::messages(),
            [
                'full_name.required' => 'الاسم الكامل مطلوب',
                'full_name.min' => 'الاسم يجب أن يكون 3 أحرف على الأقل',
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
                'email.unique' => 'هذا البريد مستخدم بالفعل',
                'phone.regex' => 'رقم الهاتف غير صالح',
                'role.required' => 'يجب اختيار نوع الحساب',
                'role.in' => 'نوع الحساب غير صالح',
                'password.required' => 'كلمة المرور مطلوبة',
                'password.confirmed' => 'كلمتا المرور غير متطابقتين',
                'terms.required' => 'يجب الموافقة على الشروط',
                'terms.accepted' => 'يجب الموافقة على الشروط',
            ]
        );
    }
}