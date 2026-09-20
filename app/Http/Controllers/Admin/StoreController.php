<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::with('merchant')->withCount('products', 'orders');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $stores = $query->latest()->paginate(15);

        $stats = [
            'all' => Store::count(),
            'active' => Store::where('status', 'active')->count(),
            'pending' => Store::where('status', 'pending')->count(),
            'inactive' => Store::where('status', 'inactive')->count(),
        ];

        return view('admin.stores.index', compact('stores', 'stats'));
    }

    public function show(Store $store)
    {
        $store->load([
            'merchant',
            'products' => fn($q) => $q->latest()->take(10),
            'orders' => fn($q) => $q->latest()->take(10),
        ]);

        $store->loadCount(['products', 'orders']);

        return view('admin.stores.show', compact('store'));
    }

    public function approve(Store $store)
    {
        $store->update(['status' => 'active']);

        return back()->with('success', 'تم اعتماد المتجر وتفعيله');
    }

    public function suspend(Store $store)
    {
        $store->update(['status' => 'inactive']);

        return back()->with('success', 'تم إيقاف المتجر مؤقتاً');
    }

    public function destroy(Store $store)
    {
        if ($store->orders()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف متجر لديه طلبات');
        }

        $store->delete();

        return redirect()->route('admin.stores.index')->with('success', 'تم حذف المتجر');
    }
}