<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = User::query();

        if (!empty($this->filters['role'])) {
            $query->where('role', $this->filters['role']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'الاسم الكامل',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'الدور',
            'الحالة',
            'البريد مُفعّل',
            'تاريخ التسجيل',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->full_name,
            $user->email,
            $user->phone ?? '—',
            $this->roleLabel($user->role),
            $this->statusLabel($user->status),
            $user->email_verified_at ? '✅ نعم' : '❌ لا',
            $user->created_at->format('Y-m-d H:i'),
        ];
    }

    protected function roleLabel(string $role): string
    {
        return match ($role) {
            'admin' => 'مشرف',
            'merchant' => 'تاجر',
            'customer' => 'زبون',
            default => $role,
        };
    }

    protected function statusLabel(string $status): string
    {
        return match ($status) {
            'active' => '✅ نشط',
            'suspended' => '⛔ معلق',
            'pending' => '⏳ قيد المراجعة',
            default => $status,
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E40AF'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 25,
            'C' => 30,
            'D' => 18,
            'E' => 12,
            'F' => 15,
            'G' => 15,
            'H' => 20,
        ];
    }
}
