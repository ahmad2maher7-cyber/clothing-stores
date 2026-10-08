<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToCollection, WithStartRow, WithChunkReading
{
    /** @var array */
    public array $results = [
        'total' => 0,
        'success' => 0,
        'failed' => 0,
        'errors' => [],
        'success_rows' => [],
    ];

    /**
     * ابدأ من الصف الثاني (تخطي الـ Header)
     */
    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * الأعمدة المتوقعة (بالترتيب):
     * [0] ID
     * [1] الاسم الكامل
     * [2] البريد الإلكتروني
     * [3] رقم الهاتف
     * [4] الدور
     * [5] الحالة
     * [6] البريد مُفعّل
     * [7] تاريخ التسجيل
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $this->results['total']++;

            // رقم الصف الفعلي (startRow = 2 => الصف الأول = 2)
            $rowNumber = $index + 2;

            // القراءة حسب الموقع (Position-based) — أكثر موثوقية
            $data = [
                'full_name' => trim((string) ($row[1] ?? '')),
                'email' => strtolower(trim((string) ($row[2] ?? ''))),
                'phone' => trim((string) ($row[3] ?? '')),
                'role' => strtolower(trim((string) ($row[4] ?? 'customer'))),
                'status' => strtolower(trim((string) ($row[5] ?? 'active'))),
            ];

            // تخطي الصفوف الفارغة تماماً
            if (empty($data['full_name']) && empty($data['email'])) {
                $this->results['total']--;  // لا نحسبها
                continue;
            }

            // تحويل الدور والحالة من العربية
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
                'role.in' => 'الدور غير صالح (admin, merchant, customer)',
                'status.in' => 'الحالة غير صالحة (active, suspended, pending)',
            ]);

            if ($validator->fails()) {
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

            // الحفظ
            try {
                $user = User::create([
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?: null,
                    'role' => $data['role'],
                    'status' => $data['status'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);

                $this->results['success']++;
                $this->results['success_rows'][] = [
                    'row' => $rowNumber,
                    'id' => $user->id,
                    'name' => $user->full_name,
                    'email' => $user->email,
                ];

            } catch (\Throwable $e) {
                $this->results['failed']++;
                $this->results['errors'][$rowNumber] = [
                    'row_data' => $data,
                    'errors' => ['خطأ في الحفظ: ' . $e->getMessage()],
                ];

                Log::error("خطأ في استيراد صف #{$rowNumber}: {$e->getMessage()}");
            }
        }
    }

    protected function normalizeRole(string $role): string
    {
        return match ($role) {
            'مشرف', 'admin' => 'admin',
            'تاجر', 'merchant' => 'merchant',
            'زبون', 'customer', '' => 'customer',
            default => $role,
        };
    }

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