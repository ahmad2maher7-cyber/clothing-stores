<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // ليس تاجراً → تجاوز
        if ($user->role !== 'merchant') {
            return $next($request);
        }

        $store = $user->stores()->first();

        // لا يوجد متجر → صفحة الإنشاء
        if (!$store) {
            if (!$request->routeIs('merchant.store.*') && !$request->routeIs('merchant.dashboard')) {
                return redirect()->route('merchant.store.create');
            }
            return $next($request);
        }

        // المتجر غير نشط → صفحة الانتظار
        if ($store->status !== 'active') {
            if (!$request->routeIs('merchant.store.*') && !$request->routeIs('merchant.dashboard')) {
                return redirect()->route('merchant.store.pending');
            }
            return $next($request);
        }

        return $next($request);
    }
}