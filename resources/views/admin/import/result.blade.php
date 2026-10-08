@extends('admin.layouts.app')

@section('title', 'نتيجة الاستيراد')
@section('page-title', 'نتيجة الاستيراد')

@section('content')

    {{-- ═══ Header ═══ --}}
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-3">
            <a href="{{ route('admin.import.index') }}" class="hover:text-forest-700 dark:hover:text-gold-400 transition-colors">
                الاستيراد
            </a>
            <span class="opacity-40">/</span>
            <span class="text-ink dark:text-cream">النتيجة</span>
        </nav>
        <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">نتيجة الاستيراد</h2>
        <p class="text-sm text-ink-muted dark:text-cream/60">تقرير مفصل لعملية استيراد {{ $type }}</p>
    </div>

    {{-- ═══ Stats ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5 text-center">
            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">📊 الإجمالي</p>
            <p class="font-display text-3xl font-bold text-ink dark:text-cream">{{ $results['total'] }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 border-2 border-green-500 dark:border-green-600 rounded-lg p-5 text-center">
            <p class="text-xs tracking-widest uppercase text-green-600 dark:text-green-400 mb-2">✅ نجحت</p>
            <p class="font-display text-3xl font-bold text-green-600 dark:text-green-400">{{ $results['success'] }}</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 border-2 {{ $results['failed'] > 0 ? 'border-red-500 dark:border-red-600' : 'border-stone-200 dark:border-stone-800' }} rounded-lg p-5 text-center">
            <p class="text-xs tracking-widest uppercase {{ $results['failed'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-ink-muted dark:text-cream/50' }} mb-2">❌ فشلت</p>
            <p class="font-display text-3xl font-bold {{ $results['failed'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-ink-muted dark:text-cream/50' }}">{{ $results['failed'] }}</p>
        </div>
    </div>

    {{-- ═══ Success Rows ═══ --}}
    @if(!empty($results['success_rows']) && count($results['success_rows']) > 0)
        <div class="bg-white dark:bg-zinc-900 border border-green-200 dark:border-green-900/50 rounded-lg overflow-hidden mb-6">
            <div class="p-4 border-b border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-950/20 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="font-display font-bold text-green-800 dark:text-green-300">
                    الصفوف المستوردة بنجاح ({{ count($results['success_rows']) }})
                </h3>
            </div>
            <div class="max-h-96 overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="bg-stone-50 dark:bg-zinc-950 border-b border-stone-200 dark:border-stone-800 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-ink-muted dark:text-cream/50">الصف</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-ink-muted dark:text-cream/50">المعرف</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-ink-muted dark:text-cream/50">الاسم</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-ink-muted dark:text-cream/50">البريد</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach($results['success_rows'] as $row)
                            <tr class="hover:bg-stone-50 dark:hover:bg-zinc-800/50">
                                <td class="px-4 py-2 text-ink-muted dark:text-cream/50">{{ $row['row'] }}</td>
                                <td class="px-4 py-2 font-mono text-xs text-forest-700 dark:text-gold-400">#{{ $row['id'] }}</td>
                                <td class="px-4 py-2 text-ink dark:text-cream">{{ $row['name'] }}</td>
                                <td class="px-4 py-2 text-ink-soft dark:text-cream/70" dir="ltr">{{ $row['email'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ═══ Failed Rows ═══ --}}
    @if(!empty($results['errors']) && count($results['errors']) > 0)
        <div class="bg-white dark:bg-zinc-900 border border-red-200 dark:border-red-900/50 rounded-lg overflow-hidden mb-6">
            <div class="p-4 border-b border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h3 class="font-display font-bold text-red-800 dark:text-red-300">
                    الصفوف التي فشلت ({{ count($results['errors']) }})
                </h3>
            </div>
            <div class="max-h-96 overflow-y-auto divide-y divide-stone-100 dark:divide-stone-800">
                @foreach($results['errors'] as $rowNumber => $error)
                    <div class="p-4 hover:bg-red-50 dark:hover:bg-red-950/10 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 flex items-center justify-center bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400 rounded font-mono text-xs shrink-0">
                                #{{ $rowNumber }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-ink dark:text-cream mb-2">
                                    {{ $error['row_data']['full_name'] ?? '—' }}
                                    <span class="text-xs text-ink-muted dark:text-cream/50 mr-2" dir="ltr">({{ $error['row_data']['email'] ?? '—' }})</span>
                                </p>
                                <ul class="space-y-1">
                                    @foreach($error['errors'] as $errMsg)
                                        <li class="text-xs text-red-700 dark:text-red-400 flex items-start gap-1.5">
                                            <span class="shrink-0">✗</span>
                                            <span>{{ $errMsg }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ═══ Actions ═══ --}}
    <div class="flex flex-wrap gap-3 justify-end">
        <a href="{{ route('admin.import.index') }}" class="btn-outline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            رفع ملف آخر
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn-solid">
            عرض المستخدمين
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
    </div>

@endsection