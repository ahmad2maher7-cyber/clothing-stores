<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    protected function getStore()
    {
        $store = auth()->user()->stores()->first();
        if (!$store) {
            abort(404, 'لا يوجد متجر مرتبط بحسابك');
        }
        return $store;
    }

    public function index()
    {
        $store = $this->getStore();

        $offers = $store->offers()->latest()->paginate(15);

        $stats = [
            'total' => $store->offers()->count(),
            'active' => $store->offers()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())->count(),
            'upcoming' => $store->offers()->where('start_date', '>', now())->count(),
            'expired' => $store->offers()->where('end_date', '<', now())->count(),
        ];

        return view('merchant.offers.index', compact('store', 'offers', 'stats'));
    }

    public function create()
    {
        $store = $this->getStore();
        return view('merchant.offers.create', compact('store'));
    }

    public function store(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('offers', 'public');
        }

        $validated['store_id'] = $store->id;

        Offer::create($validated);

        return redirect()
            ->route('merchant.offers.index')
            ->with('success', 'تم إنشاء العرض بنجاح');
    }

    public function edit(Offer $offer)
    {
        $store = $this->getStore();
        if ($offer->store_id !== $store->id) {
            abort(403);
        }

        return view('merchant.offers.edit', compact('store', 'offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $store = $this->getStore();
        if ($offer->store_id !== $store->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if ($request->hasFile('banner')) {
            if ($offer->banner) {
                Storage::disk('public')->delete($offer->banner);
            }
            $validated['banner'] = $request->file('banner')->store('offers', 'public');
        }

        $offer->update($validated);

        return redirect()
            ->route('merchant.offers.index')
            ->with('success', 'تم تحديث العرض بنجاح');
    }

    public function destroy(Offer $offer)
    {
        $store = $this->getStore();
        if ($offer->store_id !== $store->id) {
            abort(403);
        }

        if ($offer->banner) {
            Storage::disk('public')->delete($offer->banner);
        }

        $offer->delete();

        return redirect()
            ->route('merchant.offers.index')
            ->with('success', 'تم حذف العرض بنجاح');
    }
}