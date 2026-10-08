<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /** @var array */
    public array $results = [
        'total' => 0,
        'success' => 0,
        'failed' => 0,
        'errors' => [],  // [row_number => [errors]]
        'success_rows' => [],
    ];

    /**
     * قراءة الملف على دفعات (100 صف في المرة)
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * معالجة مجموعة صفوف
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $this->results['total']++;

            // رقم الصف الفعلي في Excel
            $rowNumber = $index + 2;  // +1 لأن الفهرس يبدأ من 0 + 1 للصف الـ Heading

            // تجهيز البيانات
            $data = [
                'full_name' => trim($row['full_name'] ?? $row['الاسم_الكامل'] ?? ''),
                'email' => strtolower(trim($row['email'] ?? $row['البريد_الإلكتروني'] ?? '')),
                'phone' => trim($row['phone'] ?? $row['رقم_الهاتف'] ?? ''),
                'role' => strtolower(trim($row['role'] ?? $row['الدور'] ?? 'customer')),
                'status' => strtolower(trim($row['status'] ?? $row['الحالة'] ?? 'active')),
            ];

            // تحويل الدور من العربية
            $data['role'] = $this->normalizeRole($data['role']);
            $data['status'] = $this->normalizeStatus($data['status']);

            // التحقق
            $validator = Validator::make($data, [
                'full_name' => 'required|string|min:3|max:255',
                'email' => 'required|email:rfc|max:255|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'role' => 'required|in:admin,merchant,customer',
                'status' => 'required|in:active,suspended,pending',
            ], [
                'full_name.required' => 'الاسم مطلوب',
                'full_name.min' => 'الاسم يجب أن يكون 3 أحرف على الأقل',
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
                'email.unique' => 'هذا البريد مستخدم بالفعل',
                'role.in' => 'الدور غير صالح',
                'status.in' => 'الحالة غير صالحة',
            ]);

            if ($validator->fails()) {
                // فشل التحقق — سجّل الأخطاء
                $this->results['failed']++;
                $this->results['errors'][$rowNumber] = [
                    'row_data' => $data,
                    'errors' => $validator->errors()->all(),
                ];

                Log::warning("فشل استيراد صف Excel #{$rowNumber}", [
                    'data' => $data,
                    'errors' => $validator->errors()->all(),
                ]);

                continue;
            }

            // محاولة الحفظ
            try {
                $user = User::create([
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?: null,
                    'role' => $data['role'],
                    'status' => $data['status'],
                    'password' => Hash::make('password'),  // كلمة مرور افتراضية
                    'email_verified_at' => now(),  // مؤكد افتراضياً
                ]);

                $this->results['success']++;
                $this->results['success_rows'][] = [
                    'row' => $rowNumber,
                    'id' => $user->id,
                    'name' => $user->full_name,
                    'email' => $user->email,
                ];

            } catch (\Throwable $e) {
                // خطأ غير متوقع
                $this->results['failed']++;
                $this->results['errors'][$rowNumber] = [
                    'row_data' => $data,
                    'errors' => ['خطأ في الحفظ: ' . $e->getMessage()],
                ];

                Log::error("خطأ في استيراد صف #{$rowNumber}: {$e->getMessage()}");
            }
        }
    }

    /**
     * تحويل الدور من العربية
     */
    protected function normalizeRole(string $role): string
    {
        return match ($role) {
            'مشرف', 'admin' => 'admin',
            'تاجر', 'merchant' => 'merchant',
            'زبون', 'customer', '' => 'customer',
            default => $role,
        };
    }

    /**
     * تحويل الحالة من العربية
     */
    protected function normalizeStatus(string $status): string
    {
        return match ($status) {
            'نشط', 'active', '' => 'active',
            'معلق', 'suspended' => 'suspended',
            'قيد المراجعة', 'pending' => 'pending',
            default => $status,
        };
    }
}