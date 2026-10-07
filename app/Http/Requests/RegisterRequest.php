<?php

namespace App\Http\Requests;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

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
            'email' => [
                'required',
                'string',
                'lowercase',
                'email:rfc,dns',
                'max:255',
                'unique:users,email',
            ],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'role' => ['required', 'in:customer,merchant'],
            'password' => ['required', 'confirmed', StrongPassword::rules()],
            'terms' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return array_merge(
            // رسائل كلمة المرور القوية
            StrongPassword::messages(),
            [
                'full_name.required' => 'الاسم الكامل مطلوب',
                'full_name.max' => 'الاسم طويل جداً',
                'full_name.min' => 'الاسم يجب أن يكون 3 أحرف على الأقل',

                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'يجب إدخال بريد إلكتروني بصيغة صحيحة (مثل example@gmail.com)',
                'email.unique' => 'هذا البريد مستخدم بالفعل',
                'email.max' => 'البريد الإلكتروني طويل جداً',
                'email.lowercase' => 'يجب أن يكون البريد بأحرف صغيرة',

                'phone.max' => 'رقم الهاتف طويل جداً',
                'phone.regex' => 'رقم الهاتف غير صالح',

                'role.required' => 'نوع الحساب مطلوب',
                'role.in' => 'نوع الحساب غير صحيح',

                'password.required' => 'كلمة المرور مطلوبة',
                'password.confirmed' => 'كلمتا المرور غير متطابقتين',

                'terms.required' => 'يجب الموافقة على الشروط والأحكام',
                'terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
            ]
        );
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