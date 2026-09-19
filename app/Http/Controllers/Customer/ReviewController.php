<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * قائمة تقييماتي
     */
    public function index()
    {
        $reviews = auth()->user()->reviews()
            ->with(['product.primaryImage', 'product.store', 'images'])
            ->latest()
            ->paginate(10);

        return view('customer.reviews.index', compact('reviews'));
    }

    /**
     * نموذج إضافة تقييم
     */
    public function create(Order $order, Product $product = null)
    {
        // التحقق من الطلب
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'يمكن التقييم فقط بعد استلام الطلب');
        }

        // جلب المنتجات من الطلب (أو منتج محدد)
        if ($product) {
            $productsToReview = collect([$product]);
        } else {
            // جلب منتجات الطلب التي لم تُقيّم بعد
            $productsToReview = $order->items()
                ->with('variant.product')
                ->get()
                ->pluck('variant.product')
                ->unique('id')
                ->filter(function ($p) use ($order) {
                    return !Review::where('customer_id', auth()->id())
                        ->where('product_id', $p->id)
                        ->where('order_id', $order->id)
                        ->exists();
                });
        }

        if ($productsToReview->isEmpty()) {
            return redirect()->route('customer.orders.show', $order)
                ->with('info', 'لقد قيّمت جميع منتجات هذا الطلب');
        }

        return view('customer.reviews.create', compact('order', 'productsToReview'));
    }

    /**
     * حفظ التقييم
     */
    public function store(Request $request, Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'delivered') {
            return back()->with('error', 'يمكن التقييم فقط بعد استلام الطلب');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'rating.required' => 'يجب اختيار تقييم',
            'rating.min' => 'التقييم من 1 إلى 5 نجوم',
            'rating.max' => 'التقييم من 1 إلى 5 نجوم',
        ]);

        // منع التكرار
        $existing = Review::where('customer_id', auth()->id())
            ->where('product_id', $validated['product_id'])
            ->where('order_id', $order->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'لقد قيّمت هذا المنتج مسبقاً');
        }

        $product = Product::findOrFail($validated['product_id']);

        try {
            DB::beginTransaction();

            // إنشاء التقييم
            $review = Review::create([
                'customer_id' => auth()->id(),
                'product_id' => $product->id,
                'store_id' => $product->store_id,
                'order_id' => $order->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'status' => 'pending', // يحتاج موافقة التاجر
            ]);

            // رفع الصور
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reviews', 'public');
                    ReviewImage::create([
                        'review_id' => $review->id,
                        'image_url' => $path,
                    ]);
                }
            }

            // تحديث متوسط تقييم المنتج
            $this->updateProductRating($product);

            DB::commit();

            return redirect()
                ->route('customer.reviews.index')
                ->with('success', 'شكراً لك! تم إرسال تقييمك وسيظهر بعد المراجعة');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * حذف تقييم
     */
    public function destroy(Review $review)
    {
        if ($review->customer_id !== auth()->id()) {
            abort(403);
        }

        // حذف الصور
        foreach ($review->images as $image) {
            Storage::disk('public')->delete($image->image_url);
        }

        $product = $review->product;
        $review->delete();

        // تحديث متوسط التقييم
        $this->updateProductRating($product);

        return redirect()
            ->route('customer.reviews.index')
            ->with('success', 'تم حذف التقييم');
    }

    /**
     * تحديث متوسط التقييم للمنتج
     */
    protected function updateProductRating(Product $product)
    {
        $avg = $product->reviews()->where('status', 'approved')->avg('rating') ?? 0;
        $product->update(['rating_avg' => round($avg, 2)]);
    }
}