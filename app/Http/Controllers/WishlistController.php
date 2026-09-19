<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlists()
            ->with('product.primaryImage', 'product.store', 'variant')
            ->latest()
            ->get();

        return view('customer.wishlist', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $existing = Wishlist::where('customer_id', auth()->id())
            ->where('product_id', $validated['product_id'])
            ->where('variant_id', $validated['variant_id'] ?? null)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'تم الحذف من المفضلة',
                'count' => auth()->user()->wishlists()->count(),
            ]);
        }

        Wishlist::create([
            'customer_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'variant_id' => $validated['variant_id'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'action' => 'added',
            'message' => 'تم الإضافة للمفضلة ❤️',
            'count' => auth()->user()->wishlists()->count(),
        ]);
    }

    public function remove(Wishlist $wishlist)
    {
        if ($wishlist->customer_id !== auth()->id()) {
            abort(403);
        }

        $wishlist->delete();

        return back()->with('success', 'تم الحذف من المفضلة');
    }
}