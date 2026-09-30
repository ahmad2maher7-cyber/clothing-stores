@extends('layouts.public')

@section('title', 'تواصل معنا')

@section('content')

    {{-- ═══ Hero ═══ --}}
    <section class="bg-stone-50 dark:bg-zinc-900/50 border-b border-stone-200 dark:border-stone-800">
        <div class="container-x py-16 text-center">
            <span class="eyebrow block mb-3">— نحن هنا من أجلك</span>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-ink dark:text-cream mb-4 tracking-tight">
                تواصل معنا
            </h1>
            <p class="text-base text-ink-muted dark:text-cream/60">
                نحن هنا لمساعدتك في أي وقت
            </p>
        </div>
    </section>

    <div class="container-x py-16">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ═══ Contact Info ═══ --}}
            <div class="lg:col-span-1 space-y-4">
                @php
                    $contacts = [
                        ['icon' => 'fa-location-dot', 'title' => 'العنوان',         'value' => 'فلسطين - غزة - شارع عمر المختار', 'dir' => 'rtl'],
                        ['icon' => 'fa-phone',        'title' => 'الهاتف',          'value' => '+970 599 000 000', 'dir' => 'ltr'],
                        ['icon' => 'fa-envelope',     'title' => 'البريد الإلكتروني', 'value' => 'info@clothing-store.com', 'dir' => 'ltr'],
                    ];
                @endphp

                @foreach($contacts as $contact)
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                        <div class="w-12 h-12 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded mb-4">
                            <i class="fa-solid {{ $contact['icon'] }} text-xl"></i>
                        </div>
                        <h3 class="font-display font-bold text-ink dark:text-cream mb-1.5">{{ $contact['title'] }}</h3>
                        <p class="text-sm text-ink-muted dark:text-cream/60" dir="{{ $contact['dir'] }}">
                            {{ $contact['value'] }}
                        </p>
                    </div>
                @endforeach

                {{-- Working Hours --}}
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-6">
                    <div class="w-12 h-12 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded mb-4">
                        <i class="fa-solid fa-clock text-xl"></i>
                    </div>
                    <h3 class="font-display font-bold text-ink dark:text-cream mb-3">أوقات العمل</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-ink-muted dark:text-cream/60">السبت - الخميس:</span>
                            <span class="text-ink dark:text-cream font-medium" dir="ltr">9ص - 10م</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ink-muted dark:text-cream/60">الجمعة:</span>
                            <span class="text-ink dark:text-cream font-medium" dir="ltr">2م - 10م</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ Contact Form ═══ --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden">

                    <div class="p-6 border-b border-stone-200 dark:border-stone-800 flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded">
                            <i class="fa-solid fa-paper-plane text-lg"></i>
                        </div>
                        <div>
                            <h2 class="font-display font-bold text-ink dark:text-cream">أرسل لنا رسالة</h2>
                            <p class="text-xs text-ink-muted dark:text-cream/50">سنرد عليك خلال 24 ساعة</p>
                        </div>
                    </div>

                    <div class="p-6">
                        @if(session('success'))
                            <div class="mb-5 px-5 py-4 text-sm bg-forest-50 dark:bg-forest-950/30 border border-forest-200 dark:border-forest-900/50 text-forest-800 dark:text-forest-300 rounded flex items-start gap-3">
                                <i class="fa-solid fa-circle-check shrink-0 mt-0.5"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('pages.contact.send') }}" method="POST" class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">الاسم <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', auth()->user()?->full_name) }}" required
                                           class="form-input">
                                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="form-label">البريد الإلكتروني <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                                           dir="ltr" class="form-input text-left">
                                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="form-label">الموضوع <span class="text-red-500">*</span></label>
                                <input type="text" name="subject" value="{{ old('subject') }}" required
                                       placeholder="مثال: استفسار عن منتج"
                                       class="form-input">
                                @error('subject') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="form-label">الرسالة <span class="text-red-500">*</span></label>
                                <textarea name="message" rows="6" required maxlength="2000"
                                          placeholder="اكتب رسالتك هنا..."
                                          class="form-input h-auto py-3 resize-none">{{ old('message') }}</textarea>
                                @error('message') <p class="form-error">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex justify-end pt-4 border-t border-stone-200 dark:border-stone-800">
                                <button type="submit" class="btn-solid">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    إرسال الرسالة
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection