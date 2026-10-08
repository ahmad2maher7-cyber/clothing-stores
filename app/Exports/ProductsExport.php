<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query() : \Illuminate\Database\Eloquent\Builder
    {
        $query = Product::with(['store', 'category', 'brand']);

        if (!empty($this->filters['store_id'])) {
            $query->where('store_id', $this->filters['store_id']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        if (!empty($this->filters['gender'])) {
            $query->where('gender', $this->filters['gender']);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'اسم المنتج',
            'المتجر',
            'التصنيف',
            'الماركة',
            'الفئة',
            'السعر الأساسي',
            'سعر الخصم',
            'العملة',
            'الحالة',
            'متوسط التقييم',
            'تاريخ الإنشاء',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->store?->name ?? '—',
            $product->category?->name ?? '—',
            $product->brand?->name ?? '—',
            $this->genderLabel($product->gender),
            number_format($product->base_price, 2),
            $product->discount_price ? number_format($product->discount_price, 2) : '—',
            $product->currency ?? 'ILS',
            $this->statusLabel($product->status),
            number_format($product->rating_avg, 1),
            $product->created_at->format('Y-m-d H:i'),
        ];
    }

    protected function genderLabel(string $gender): string
    {
        return match ($gender) {
            'men' => 'رجالي',
            'women' => 'نسائي',
            'kids' => 'أطفال',
            'unisex' => 'للجنسين',
            default => $gender,
        };
    }

    protected function statusLabel(string $status): string
    {
        return match ($status) {
            'active' => '✅ نشط',
            'draft' => '📝 مسودة',
            'out_of_stock' => '⛔ نفد',
            default => $status,
        };
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'],
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 30,
            'C' => 22,
            'D' => 18,
            'E' => 15,
            'F' => 12,
            'G' => 15,
            'H' => 15,
            'I' => 10,
            'J' => 15,
            'K' => 12,
            'L' => 20,
        ];
    }
}