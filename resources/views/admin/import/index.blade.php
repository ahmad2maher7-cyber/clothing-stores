@extends('admin.layouts.app')

@section('title', 'استيراد البيانات')
@section('page-title', 'استيراد البيانات')

@section('content')

    {{-- ═══ Header ═══ --}}
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="font-display text-2xl font-bold text-ink dark:text-cream">📥 استيراد البيانات</h2>
            <p class="text-sm text-ink-muted dark:text-cream/60">استيراد البيانات من ملفات Excel / CSV</p>
        </div>
        <a href="{{ route('export.users') }}"
           class="inline-flex items-center gap-2 h-11 px-5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded transition-colors whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            📤 تصدير نموذج
        </a>
    </div>

    {{-- ═══ Info Card ═══ --}}
    <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/50 rounded-lg p-5 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-800 dark:text-blue-300">
                <p class="font-semibold mb-1">📋 إرشادات الاستيراد</p>
                <ul class="space-y-1 text-xs leading-relaxed">
                    <li>• الصفوف الصحيحة <strong>ستُدخَل</strong> تلقائياً</li>
                    <li>• الصفوف الخاطئة <strong>ستُتخطّى</strong> ولن توقف العملية</li>
                    <li>• في النهاية ستحصل على تقرير مفصل بالنتائج</li>
                    <li>• كلمة المرور الافتراضية: <code class="bg-white dark:bg-zinc-900 px-1.5 py-0.5 rounded font-mono">password</code></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ═══ Import Cards ═══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Users Import --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
            <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                <div class="w-12 h-12 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-display text-lg font-bold text-ink dark:text-cream">المستخدمون</h3>
                    <p class="text-xs text-ink-muted dark:text-cream/60">استيراد المستخدمين من Excel</p>
                </div>
            </div>

            <form action="{{ route('admin.import.users') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="form-label">ملف Excel أو CSV <span class="text-red-500">*</span></label>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                           class="form-input file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-forest-50 dark:file:bg-forest-950/40 file:text-forest-700 dark:file:text-gold-400 hover:file:bg-forest-100">
                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">
                        xlsx, xls, csv — أقل من 10MB
                    </p>
                    @error('file') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Expected Columns --}}
                <div class="bg-stone-50 dark:bg-zinc-950 border border-stone-200 dark:border-stone-800 rounded p-4">
                    <p class="text-xs font-semibold text-ink dark:text-cream mb-2">الأعمدة المتوقعة:</p>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="px-2 py-1 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-700 rounded font-mono">الاسم الكامل</span>
                        <span class="px-2 py-1 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-700 rounded font-mono">البريد الإلكتروني</span>
                        <span class="px-2 py-1 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-700 rounded font-mono">رقم الهاتف</span>
                        <span class="px-2 py-1 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-700 rounded font-mono">الدور</span>
                        <span class="px-2 py-1 bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-700 rounded font-mono">الحالة</span>
                    </div>
                </div>

                <button type="submit" class="btn-solid w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    📥 بدء الاستيراد
                </button>
            </form>
        </div>

        {{-- Coming Soon — Products Import --}}
        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden opacity-60">
            <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                <div class="w-12 h-12 flex items-center justify-center bg-stone-100 dark:bg-zinc-800 text-ink-muted dark:text-cream/50 rounded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-display text-lg font-bold text-ink dark:text-cream">المنتجات</h3>
                    <p class="text-xs text-ink-muted dark:text-cream/60">قريباً</p>
                </div>
            </div>
            <div class="p-6 text-center">
                <p class="text-sm text-ink-muted dark:text-cream/50">🚧 سيتم إضافتها قريباً</p>
            </div>
        </div>
    </div>

@endsection