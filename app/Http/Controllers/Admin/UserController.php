<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // فلترة حسب الدور
        if ($request->role) {
            $query->where('role', $request->role);
        }

        // فلترة حسب الحالة
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // بحث
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(20);

        $stats = [
            'all' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'merchants' => User::where('role', 'merchant')->count(),
            'customers' => User::where('role', 'customer')->count(),
            'active' => User::where('status', 'active')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['stores', 'orders' => fn($q) => $q->latest()->take(5)]);

        return view('admin.users.show', compact('user'));
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك تعليق حسابك');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'suspended' : 'active',
        ]);

        return back()->with('success', 
            $user->status === 'active' ? 'تم تفعيل الحساب' : 'تم تعليق الحساب'
        );
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'لا يمكن حذف حساب مشرف آخر');
        }

        // تحقق من وجود طلبات
        if ($user->orders()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف مستخدم لديه طلبات');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم');
    }
}