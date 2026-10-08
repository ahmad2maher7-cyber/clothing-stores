<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Order::with(['customer', 'store', 'paymentMethod']);

        if (!empty($this->filters['store_id'])) {
            $query->where('store_id', $this->filters['store_id']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['payment_status'])) {
            $query->where('payment_status', $this->filters['payment_status']);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'رقم الطلب',
            'الزبون',
            'المتجر',
            'المجموع الفرعي',
            'الخصم',
            'تكلفة الشحن',
            'الإجمالي',
            'طريقة الدفع',
            'حالة الطلب',
            'حالة الدفع',
            'عنوان التوصيل',
            'تاريخ الطلب',
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->order_number,
            $order->customer?->full_name ?? '—',
            $order->store?->name ?? '—',
            number_format($order->subtotal, 2),
            number_format($order->discount, 2),
            number_format($order->shipping_cost, 2),
            number_format($order->total, 2),
            $order->paymentMethod?->name ?? '—',
            $this->statusLabel($order->status),
            $this->paymentStatusLabel($order->payment_status),
            $order->shipping_address,
            $order->created_at->format('Y-m-d H:i'),
        ];
    }

    protected function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => '⏳ قيد المراجعة',
            'processing' => '⚙️ التجهيز',
            'shipped' => '🚚 الشحن',
            'delivering' => '📍 التوصيل',
            'delivered' => '✅ التسليم',
            'cancelled' => '❌ ملغى',
            'returned' => '↩️ مرتجع',
            default => $status,
        };
    }

    protected function paymentStatusLabel(string $status): string
    {
        return match ($status) {
            'unpaid' => '⏳ غير مدفوع',
            'paid' => '✅ مدفوع',
            'refunded' => '↩️ مُسترجع',
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
                    'startColor' => ['rgb' => '7C3AED'],
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 20,
            'C' => 22,
            'D' => 22,
            'E' => 15,
            'F' => 12,
            'G' => 15,
            'H' => 15,
            'I' => 18,
            'J' => 18,
            'K' => 15,
            'L' => 35,
            'M' => 20,
        ];
    }
}