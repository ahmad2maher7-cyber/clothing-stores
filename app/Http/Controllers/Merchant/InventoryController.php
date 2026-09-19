<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    protected function getStore()
    {
        $store = auth()->user()->stores()->first();
        if (!$store) {
            abort(404, 'لا يوجد متجر مرتبط بحسابك');
        }
        return $store;
    }

    /**
     * عرض جميع المنتجات مع مخزونها
     */
    public function index(Request $request)
    {
        $store = $this->getStore();

        // جلب المتغيرات التي تنتمي لمنتجات هذا المتجر
        $query = ProductVariant::whereHas('product', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })->with('product.category');

        // بحث
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('color', 'like', '%' . $request->search . '%')
                  ->orWhereHas('product', function ($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // فلترة حسب الحالة
        if ($request->filter === 'low_stock') {
            $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                  ->where('stock_quantity', '>', 0);
        } elseif ($request->filter === 'out_of_stock') {
            $query->where('stock_quantity', '<=', 0);
        } elseif ($request->filter === 'in_stock') {
            $query->where('stock_quantity', '>', 0);
        }

        // ترتيب حسب الأقل مخزوناً
        if ($request->sort === 'lowest') {
            $query->orderBy('stock_quantity', 'asc');
        } else {
            $query->latest();
        }

        $variants = $query->paginate(20);

        // إحصائيات
        $allVariants = ProductVariant::whereHas('product', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        });

        $stats = [
            'total_variants' => $allVariants->count(),
            'total_stock' => $allVariants->sum('stock_quantity'),
            'low_stock' => (clone $allVariants)->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                ->where('stock_quantity', '>', 0)->count(),
            'out_of_stock' => (clone $allVariants)->where('stock_quantity', '<=', 0)->count(),
            'total_value' => $allVariants->sum(DB::raw('stock_quantity * price')),
        ];

        return view('merchant.inventory.index', compact('store', 'variants', 'stats'));
    }

    /**
     * تحديث كمية المخزون (AJAX)
     */
    public function updateStock(Request $request, ProductVariant $variant)
    {
        $store = $this->getStore();

        // التأكد من أن المتغير يخص المتجر
        if ($variant->product->store_id !== $store->id) {
            return response()->json(['error' => 'غير مصرح'], 403);
        }

        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $oldQuantity = $variant->stock_quantity;
            $newQuantity = $validated['stock_quantity'];
            $difference = $newQuantity - $oldQuantity;

            // تحديث الكمية
            $variant->update([
                'stock_quantity' => $newQuantity,
                'low_stock_threshold' => $validated['low_stock_threshold'] ?? $variant->low_stock_threshold,
            ]);

            // تسجيل الحركة إن تغيرت الكمية
            if ($difference !== 0) {
                InventoryLog::create([
                    'variant_id' => $variant->id,
                    'change_type' => $difference > 0 ? 'in' : 'out',
                    'quantity' => abs($difference),
                    'reason' => $validated['reason'] ?? 'تعديل يدوي بواسطة التاجر',
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المخزون بنجاح',
                'new_quantity' => $variant->fresh()->stock_quantity,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * سجل حركات المخزون
     */
    public function logs(Request $request, ProductVariant $variant = null)
    {
        $store = $this->getStore();

        $query = InventoryLog::whereHas('variant.product', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })->with(['variant.product', 'createdBy']);

        if ($variant && $variant->product->store_id === $store->id) {
            $query->where('variant_id', $variant->id);
        }

        $logs = $query->latest()->paginate(30);

        return view('merchant.inventory.logs', compact('store', 'logs', 'variant'));
    }

    /**
     * صفحة المنتجات منخفضة المخزون
     */
    public function lowStock()
    {
        $store = $this->getStore();

        $variants = ProductVariant::whereHas('product', function ($q) use ($store) {
            $q->where('store_id', $store->id);
        })
        ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
        ->with('product.category')
        ->orderBy('stock_quantity', 'asc')
        ->get();

        return view('merchant.inventory.low-stock', compact('store', 'variants'));
    }
}