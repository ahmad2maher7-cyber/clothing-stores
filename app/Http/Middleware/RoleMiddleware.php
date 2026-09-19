<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * الاستخدام: middleware('role:admin') أو middleware('role:admin,merchant')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
        }

        if (auth()->user()->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'حسابك موقوف. تواصل مع الدعم.']);
        }

        return $next($request);
    }
}