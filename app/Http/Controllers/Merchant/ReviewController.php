<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    protected function getStore()
    {
        $store = auth()->user()->stores()->first();
        if (!$store) {
            abort(404, 'لا يوجد متجر');
        }
        return $store;
    }

    public function index(Request $request)
    {
        $store = $this->getStore();

        $query = $store->reviews()
            ->with(['customer', 'product.primaryImage', 'images']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->rating) {
            $query->where('rating', $request->rating);
        }

        if ($request->search) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('customer', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%');
            });
        }

        $reviews = $query->latest()->paginate(15);

        $stats = [
            'total' => $store->reviews()->count(),
            'pending' => $store->reviews()->where('status', 'pending')->count(),
            'approved' => $store->reviews()->where('status', 'approved')->count(),
            'rejected' => $store->reviews()->where('status', 'rejected')->count(),
            'avg_rating' => $store->reviews()->where('status', 'approved')->avg('rating') ?? 0,
        ];

        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = $store->reviews()
                ->where('status', 'approved')
                ->where('rating', $i)
                ->count();
        }

        return view('merchant.reviews.index', compact(
            'store',
            'reviews',
            'stats',
            'ratingDistribution'
        ));
    }

    public function approve(Review $review)
    {
        $store = $this->getStore();
        if ($review->store_id !== $store->id) {
            abort(403);
        }

        $review->update(['status' => 'approved']);
        $this->updateProductRating($review->product);

        // ═══════════════════════════════════════
        //  الإشعارات (خارج try/catch)
        // ═══════════════════════════════════════
        try {
            \App\Services\NotificationService::send(
                userId: $review->customer_id,
                title: '✅ تم اعتماد تقييمك',
                body: "تم نشر تقييمك على المنتج: {$review->product->name}. شكراً لك!",
                type: 'review_approved'
            );
        } catch (\Exception $e) {
            \Log::error('Review approve notification failed: ' . $e->getMessage());
        }

        return back()->with('success', 'تم اعتماد التقييم ونشره');
    }

    public function reject(Review $review)
    {
        $store = $this->getStore();
        if ($review->store_id !== $store->id) {
            abort(403);
        }

        $review->update(['status' => 'rejected']);
        $this->updateProductRating($review->product);

        return back()->with('success', 'تم رفض التقييم');
    }

    public function destroy(Review $review)
    {
        $store = $this->getStore();
        if ($review->store_id !== $store->id) {
            abort(403);
        }

        $product = $review->product;
        $review->delete();
        $this->updateProductRating($product);

        return back()->with('success', 'تم حذف التقييم');
    }

    protected function updateProductRating(Product $product)
    {
        $avg = $product->reviews()->where('status', 'approved')->avg('rating') ?? 0;
        $product->update(['rating_avg' => round($avg, 2)]);
    }
}