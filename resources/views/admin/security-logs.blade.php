@extends('admin.layouts.app')

@section('title', 'سجلات الأمان')
@section('page-title', 'سجلات الأمان')

@section('content')

    {{-- ═══ Header ═══ --}}
    <div class="mb-8">
        <span class="eyebrow block mb-3">— الأمان</span>
        <h2 class="font-display text-3xl font-bold text-ink dark:text-cream mb-2">
            سجلات الأمان
        </h2>
        <p class="text-sm text-ink-muted dark:text-cream/60">متابعة المحاولات المشبوهة في آخر 24 ساعة</p>
    </div>

    {{-- ═══ Stats ═══ --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">

        {{-- Total --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">إجمالي (24 ساعة)</span>
                <div class="w-8 h-8 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 text-ink-muted dark:text-cream/60 rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $stats['total_24h'] }}</p>
        </div>

        {{-- Login Failures --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">محاولات دخول</span>
                <div class="w-8 h-8 flex items-center justify-center bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
            <p class="font-display text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['login_failures'] }}</p>
        </div>

        {{-- OTP Failures --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">محاولات OTP</span>
                <div class="w-8 h-8 flex items-center justify-center bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="font-display text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['otp_failures'] }}</p>
        </div>

        {{-- Password Reset --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">إعادة تعيين</span>
                <div class="w-8 h-8 flex items-center justify-center bg-violet-50 dark:bg-violet-950/30 text-violet-600 dark:text-violet-400 rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
            </div>
            <p class="font-display text-3xl font-bold text-violet-600 dark:text-violet-400">{{ $stats['password_reset_attempts'] }}</p>
        </div>

        {{-- Unique IPs --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">IP فريدة</span>
                <div class="w-8 h-8 flex items-center justify-center bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                </div>
            </div>
            <p class="font-display text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['unique_ips'] }}</p>
        </div>
    </div>

    {{-- ═══ Logs Table ═══ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">

        @if($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">النوع</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">البريد الإلكتروني</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">IP</th>
                            <th class="px-4 py-3 text-right text-xs tracking-widest uppercase font-semibold text-ink-muted dark:text-cream/50">الوقت</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($logs as $log)
                            @php
                                $typeLabels = [
                                    'login' => ['تسجيل دخول', 'badge-danger'],
                                    'otp' => ['كود OTP', 'badge-stone'],
                                    'password_reset_blocked' => ['حظر إعادة تعيين', 'badge-danger'],
                                    'password_reset_invalid_email' => ['بريد غير مسجل', 'badge-stone'],
                                    'password_reset_invalid' => ['إعادة تعيين خاطئة', 'badge-stone'],
                                ];
                                [$typeLabel, $badgeClass] = $typeLabels[$log->type] ?? [$log->type, 'badge-stone'];
                            @endphp
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="badge {{ $badgeClass }} whitespace-nowrap">
                                        {{ $typeLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($log->email)
                                        <p class="text-ink dark:text-cream">{{ $log->email }}</p>
                                    @else
                                        <span class="text-ink-muted dark:text-cream/40">—</span>
                                    @endif
                                    @if($log->user)
                                        <p class="text-xs text-ink-muted dark:text-cream/50 mt-0.5">
                                            {{ $log->user->full_name }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-ink-muted dark:text-cream/60 whitespace-nowrap">
                                    {{ $log->ip_address ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-xs text-ink-muted dark:text-cream/60 whitespace-nowrap">
                                    <p>{{ $log->created_at->format('Y-m-d H:i:s') }}</p>
                                    <p class="text-[10px] text-ink-faint dark:text-cream/40 mt-0.5">
                                        {{ $log->created_at->diffForHumans() }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-stone-200 dark:border-stone-800">
                {{ $logs->links() }}
            </div>
        @else
            {{-- ═══ Empty State ═══ --}}
            <div class="text-center py-20">
                <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 rounded-full">
                    <svg class="w-10 h-10 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream mb-2">لا توجد سجلات أمان</h3>
                <p class="text-sm text-ink-muted dark:text-cream/60">
                    لم يتم تسجيل أي محاولات مشبوهة في آخر 24 ساعة
                </p>
            </div>
        @endif
    </div>

@endsection