@extends('admin.layouts.app')

@section('title', 'المستخدمون')
@section('page-title', 'المستخدمون')

@section('content')

    {{-- ═══ Header ═══ --}}
    <div class="mb-6">
        <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">المستخدمون</h2>
        <p class="text-sm text-ink-muted dark:text-cream/60">إدارة جميع مستخدمي المنصة</p>
    </div>

    {{-- ═══ Stats ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">

        {{-- All --}}
        <a href="{{ route('admin.users.index') }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-4 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ !request('role') ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">الكل</p>
            <p class="font-display text-2xl font-bold text-ink dark:text-cream">{{ $stats['all'] }}</p>
        </a>

        {{-- Admin --}}
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-4 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('role') == 'admin' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">🛡️ مشرف</p>
            <p class="font-display text-2xl font-bold text-ink dark:text-cream">{{ $stats['admins'] }}</p>
        </a>

        {{-- Merchant --}}
        <a href="{{ route('admin.users.index', ['role' => 'merchant']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-4 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('role') == 'merchant' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">🏪 تجار</p>
            <p class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400">{{ $stats['merchants'] }}</p>
        </a>

        {{-- Customer --}}
        <a href="{{ route('admin.users.index', ['role' => 'customer']) }}"
           class="bg-white dark:bg-zinc-900 border rounded-lg p-4 text-center hover:border-forest-500 dark:hover:border-gold-500 transition
                  {{ request('role') == 'customer' ? 'border-forest-700 dark:border-gold-400' : 'border-stone-200 dark:border-stone-800' }}">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">👤 زبائن</p>
            <p class="font-display text-2xl font-bold text-ink dark:text-cream">{{ $stats['customers'] }}</p>
        </a>

        {{-- Active --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 text-center">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">✅ نشط</p>
            <p class="font-display text-2xl font-bold text-forest-700 dark:text-gold-400">{{ $stats['active'] }}</p>
        </div>

        {{-- Suspended --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 text-center">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">⛔ معلق</p>
            <p class="font-display text-2xl font-bold text-red-600 dark:text-red-400">{{ $stats['suspended'] }}</p>
        </div>
    </div>

    {{-- ═══ Search ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-2">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="ابحث بالاسم، البريد، أو الهاتف..."
                       class="form-input pl-10">
                <svg class="w-4 h-4 text-ink-muted dark:text-cream/40 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="btn-solid">بحث</button>
            <a href="{{ route('admin.users.index') }}" class="btn-outline text-center">إعادة</a>
        </form>
    </div>

    {{-- ═══ Table ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">المستخدم</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">التواصل</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الدور</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">التسجيل</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($users as $user)
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ mb_substr($user->full_name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-medium text-ink dark:text-cream truncate">{{ $user->full_name }}</p>
                                            <p class="text-xs text-ink-muted dark:text-cream/50">#{{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-ink-soft dark:text-cream/70">{{ $user->email }}</p>
                                    @if($user->phone)
                                        <p class="text-xs text-ink-muted dark:text-cream/40" dir="ltr">{{ $user->phone }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="badge badge-dark">🛡️ مشرف</span>
                                            @break
                                        @case('merchant')
                                            <span class="badge badge-forest">🏪 تاجر</span>
                                            @break
                                        @default
                                            <span class="badge badge-stone">👤 زبون</span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-3">
                                    @if($user->status === 'active')
                                        <span class="badge badge-forest">نشط</span>
                                    @else
                                        <span class="badge badge-danger">معلق</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-ink-muted dark:text-cream/50 whitespace-nowrap">
                                    {{ $user->created_at->format('Y/m/d') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                           class="inline-flex items-center gap-1 text-xs font-medium text-forest-700 dark:text-gold-400 hover:gap-2 transition-all">
                                            عرض
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </a>
                                        @if($user->id !== auth()->id() && $user->role !== 'admin')
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <button type="submit"
                                                        class="text-xs font-medium transition-colors {{ $user->status === 'active' ? 'text-red-600 dark:text-red-400 hover:text-red-700' : 'text-forest-700 dark:text-gold-400 hover:opacity-70' }}">
                                                    {{ $user->status === 'active' ? '⛔ تعليق' : '✓ تفعيل' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-stone-200 dark:border-stone-800">
                {{ $users->links() }}
            </div>
        @else
            {{-- ═══ Empty State ═══ --}}
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 rounded-full">
                    <svg class="w-10 h-10 text-ink-muted dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا يوجد مستخدمون</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">جرب البحث بكلمات مختلفة</p>
            </div>
        @endif
    </div>

@endsection