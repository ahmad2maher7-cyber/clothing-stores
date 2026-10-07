@extends('merchant.layouts.app')

@section('title', 'إعدادات المتجر')
@section('page-title', 'إعدادات المتجر')

@section('content')

    {{-- Tabs --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden mb-6">
        <div class="flex overflow-x-auto">
            @php
                $tabs = [
                    ['route' => 'merchant.settings.index',       'label' => 'المعلومات الأساسية', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['route' => 'merchant.settings.appearance',  'label' => 'الهوية البصرية',    'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
                    ['route' => 'merchant.settings.branches',    'label' => 'الفروع',      'icon' => 'M3 21h18M5 21V7l8-4v18M19 21V11l-6-4M9 9v.01M9 12v.01M9 15v.01M9 18v.01'],
                    ['route' => 'merchant.settings.shipping',    'label' => 'مناطق الشحن',        'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                    ['route' => 'merchant.settings.policies',    'label' => 'السياسات',        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ];
            @endphp

            @foreach($tabs as $tab)
                @php $isActive = request()->routeIs($tab['route']); @endphp
                <a href="{{ route($tab['route']) }}"
                   class="flex items-center gap-2 px-5 py-4 whitespace-nowrap border-b-2 text-sm font-medium transition-colors
                          {{ $isActive 
                             ? 'border-forest-700 dark:border-gold-400 text-forest-700 dark:text-gold-400' 
                             : 'border-transparent text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}"/>
                    </svg>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <form action="{{ route('merchant.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main Column --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Basic Info --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display text-lg font-bold text-ink dark:text-cream">المعلومات الأساسية</h3>
                            <p class="text-xs text-ink-muted dark:text-cream/60">بيانات متجرك الأساسية</p>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        <div>
                            <label class="form-label">
                                اسم المتجر <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $store->name) }}" required
                                   class="form-input">
                            @error('name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">وصف المتجر</label>
                            <textarea name="description" rows="4" maxlength="1000"
                                      placeholder="اكتب وصفاً جاذباً لمتجرك..."
                                      class="form-input h-auto py-3 resize-none">{{ old('description', $store->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">السجل التجاري</label>
                                <input type="text" name="commercial_register" 
                                       value="{{ old('commercial_register', $store->commercial_register) }}"
                                       placeholder="CR-12345"
                                       class="form-input">
                            </div>
                            <div>
                                <label class="form-label">رقم الهاتف</label>
                                <input type="text" value="{{ auth()->user()->phone }}" disabled
                                       class="form-input bg-stone-50 dark:bg-zinc-800 cursor-not-allowed opacity-70">
                                <p class="text-xs text-ink-muted dark:text-cream/40 mt-2">يُعدل من الملف الشخصي</p>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">العنوان الرئيسي</label>
                            <textarea name="address" rows="2"
                                      placeholder="المدينة - الحي - الشارع"
                                      class="form-input h-auto py-3 resize-none">{{ old('address', $store->address) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Working Hours --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display text-lg font-bold text-ink dark:text-cream">أوقات العمل</h3>
                            <p class="text-xs text-ink-muted dark:text-cream/60">اتركها فارغة للأيام المغلقة</p>
                        </div>
                    </div>

                    <div class="p-6">
                        @php
                            $days = [
                                'saturday' => 'السبت',
                                'sunday' => 'الأحد',
                                'monday' => 'الاثنين',
                                'tuesday' => 'الثلاثاء',
                                'wednesday' => 'الأربعاء',
                                'thursday' => 'الخميس',
                                'friday' => 'الجمعة',
                            ];
                            $workingHours = $store->working_hours ?? [];
                        @endphp

                        <div class="space-y-3">
                            @foreach($days as $key => $label)
                                <div class="flex items-center gap-3">
                                    <div class="w-24 text-sm font-medium text-ink dark:text-cream shrink-0">{{ $label }}</div>
                                    <input type="text"
                                           name="working_hours[{{ $key }}]"
                                           value="{{ old('working_hours.' . $key, $workingHours[$key] ?? '') }}"
                                           placeholder="09:00 - 22:00 أو CLOSED"
                                           class="form-input flex-1 text-sm">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Logo --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                    <h3 class="font-display font-bold text-ink dark:text-cream mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        شعار المتجر
                    </h3>

                    <div class="text-center">
                        @if($store->logo)
                            <img src="{{ $store->logo_url }}"
                                 alt="{{ $store->name }}"
                                 class="w-28 h-28 rounded-full mx-auto object-cover border-4 border-stone-100 dark:border-zinc-800 mb-4">
                        @else
                            <div class="w-28 h-28 rounded-full bg-forest-50 dark:bg-forest-950/40 flex items-center justify-center mx-auto mb-4 border-4 border-stone-100 dark:border-zinc-800">
                                <svg class="w-12 h-12 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif

                        <label for="logo" class="btn-outline cursor-pointer inline-flex">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            تغيير الشعار
                        </label>
                        <input type="file" name="logo" id="logo" accept="image/*" class="hidden"
                               onchange="document.getElementById('logo-name').textContent = this.files[0].name">
                        <p id="logo-name" class="text-xs text-ink-muted dark:text-cream/50 mt-2"></p>
                    </div>
                    @error('logo') <p class="form-error mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Banner --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                    <h3 class="font-display font-bold text-ink dark:text-cream mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-forest-700 dark:text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        غلاف المتجر
                    </h3>

                    @if($store->banner)
                        <img src="{{ $store->banner_url }}"
                             alt="{{ $store->name }}"
                             class="w-full h-32 object-cover rounded mb-3 border border-stone-200 dark:border-stone-800">
                    @else
                        <div class="w-full h-32 bg-gradient-to-br from-forest-700 to-gold-500 rounded mb-3 flex items-center justify-center">
                            <svg class="w-10 h-10 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif

                    <label for="banner" class="btn-outline cursor-pointer w-full justify-center inline-flex">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        تغيير الغلاف
                    </label>
                    <input type="file" name="banner" id="banner" accept="image/*" class="hidden"
                           onchange="document.getElementById('banner-name').textContent = this.files[0].name">
                    <p id="banner-name" class="text-xs text-ink-muted dark:text-cream/50 mt-2"></p>

                    <p class="text-xs text-ink-muted dark:text-cream/40 mt-3">المقاس المقترح: 1200x400 بكسل</p>
                    @error('banner') <p class="form-error mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Store Info --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-5">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-ink-muted dark:text-cream/50">حالة المتجر:</span>
                            @if($store->status === 'active')
                                <span class="badge badge-forest">✅ نشط</span>
                            @else
                                <span class="badge badge-danger">⛔ معطل</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-ink-muted dark:text-cream/50">تاريخ الإنشاء:</span>
                            <span class="font-medium text-ink dark:text-cream">{{ $store->created_at->format('Y/m/d') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-ink-muted dark:text-cream/50">معرّف المتجر:</span>
                            <span class="font-mono text-xs text-ink dark:text-cream">#{{ $store->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end mt-6">
            <button type="submit" class="btn-solid">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                حفظ الإعدادات
            </button>
        </div>
    </form>

    {{-- ═══════════════════════════════════════════════════ --}}
    {{--  تغيير كلمة المرور  --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden mt-6">
        <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
            <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-display text-lg font-bold text-ink dark:text-cream">تغيير كلمة المرور</h3>
                <p class="text-xs text-ink-muted dark:text-cream/60">احرص على استخدام كلمة مرور قوية</p>
            </div>
        </div>

        <form action="{{ route('merchant.settings.password') }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">
                    كلمة المرور الحالية <span class="text-red-500">*</span>
                </label>
                <input type="password" name="current_password" required
                       class="form-input"
                       placeholder="أدخل كلمة المرور الحالية">
                @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">
                        كلمة المرور الجديدة <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required minlength="8"
                           class="form-input"
                           placeholder="8 أحرف على الأقل">
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">
                        تأكيد كلمة المرور <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required minlength="8"
                           class="form-input"
                           placeholder="أعد كتابة كلمة المرور">
                </div>
            </div>

            {{-- Info Box --}}
            <div class="bg-stone-50 dark:bg-zinc-950/40 border border-stone-200 dark:border-stone-800 rounded p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-forest-700 dark:text-gold-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-xs text-ink-muted dark:text-cream/60 leading-relaxed">
                    <p class="font-medium text-ink dark:text-cream mb-1">متطلبات كلمة المرور:</p>
                    <ul class="space-y-1">
                        <li>• 8 أحرف على الأقل</li>
                        <li>• يُنصح باستخدام أحرف كبيرة وأرقام ورموز</li>
                        <li>• ستصلك رسالة على بريدك عند تغيير كلمة المرور</li>
                    </ul>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-stone-200 dark:border-stone-800">
                <button type="submit" class="btn-solid">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    تحديث كلمة المرور
                </button>
            </div>
        </form>
    </div>

@endsection