<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * الحصول على سلة المستخدم الحالية
     */
    protected function getCart()
    {
        $cart = auth()->user()->carts()->latest()->first();
        if (!$cart) {
            $cart = Cart::create([
                'customer_id' => auth()->id(),
                'store_id' => null, // سلة عامة لكل المتاجر
            ]);
        }
        return $cart;
    }

    /**
     * عرض السلة
     */
    public function index()
    {
        $cart = $this->getCart();
        $cart->load('items.variant.product.store', 'items.variant.product.primaryImage');

        // تجميع حسب المتجر
        $itemsByStore = $cart->items->groupBy(function ($item) {
            return $item->variant->product->store_id;
        });

        $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);

        return view('cart.index', compact('cart', 'itemsByStore', 'subtotal'));
    }

    /**
     * إضافة منتج للسلة (AJAX)
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'color' => 'required|string',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // البحث عن المتغير المناسب
        $variant = ProductVariant::where('product_id', $product->id)
            ->where('size', $validated['size'])
            ->where('color', $validated['color'])
            ->first();

        if (!$variant) {
            return response()->json([
                'success' => false,
                'message' => 'المقاس واللون المختار غير متوفر',
            ], 400);
        }

        if ($variant->stock_quantity < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'الكمية المطلوبة غير متوفرة. المتاح: ' . $variant->stock_quantity,
            ], 400);
        }

        try {
            DB::beginTransaction();

            $cart = $this->getCart();

            // البحث عن نفس المنتج في السلة
            $existingItem = $cart->items()
                ->where('variant_id', $variant->id)
                ->first();

            if ($existingItem) {
                // تحديث الكمية
                $newQuantity = $existingItem->quantity + $validated['quantity'];
                if ($newQuantity > $variant->stock_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'لا يمكن إضافة المزيد. المتاح: ' . $variant->stock_quantity,
                    ], 400);
                }
                $existingItem->update(['quantity' => $newQuantity]);
            } else {
                // إضافة جديد
                CartItem::create([
                    'cart_id' => $cart->id,
                    'variant_id' => $variant->id,
                    'quantity' => $validated['quantity'],
                    'price' => $variant->discount_price ?? $variant->price,
                ]);
            }

            DB::commit();

            $cartCount = $cart->items()->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المنتج للسلة',
                'cart_count' => $cartCount,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * تحديث كمية عنصر (AJAX)
     */
    public function update(Request $request, CartItem $item)
    {
        if ($item->cart->customer_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        if ($item->variant->stock_quantity < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'الكمية غير متوفرة. المتاح: ' . $item->variant->stock_quantity,
            ], 400);
        }

        $item->update(['quantity' => $validated['quantity']]);

        $cart = $item->cart;
        $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الكمية',
            'item_subtotal' => $item->price * $item->quantity,
            'cart_subtotal' => $subtotal,
        ]);
    }

    /**
     * حذف عنصر من السلة (AJAX)
     */
    public function remove(CartItem $item)
    {
        if ($item->cart->customer_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
        }

        $item->delete();

        $cart = $item->cart;
        $subtotal = $cart->items->sum(fn($i) => $i->price * $i->quantity);
        $cartCount = $cart->items()->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المنتج من السلة',
            'cart_subtotal' => $subtotal,
            'cart_count' => $cartCount,
        ]);
    }

    /**
     * الحصول على عدد المنتجات في السلة (AJAX)
     */
    public function count()
    {
        $cart = auth()->user()->carts()->latest()->first();
        $count = $cart ? $cart->items()->sum('quantity') : 0;

        return response()->json(['count' => $count]);
    }
}