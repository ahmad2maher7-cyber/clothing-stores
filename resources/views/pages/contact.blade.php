@extends('layouts.public')

@section('title', 'تواصل معنا')

@section('content')

    <section style="background-color: var(--gold-soft);">
        <div class="max-w-[1400px] mx-auto px-4 py-16 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" style="color: var(--text-primary);">
                تواصل معنا 📞
            </h1>
            <p class="text-lg" style="color: var(--text-secondary);">
                نحن هنا لمساعدتك، تواصل معنا بأي طريقة تفضلها
            </p>
        </div>
    </section>

    <div class="max-w-[1200px] mx-auto px-4 py-14">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Contact Info --}}
            <div class="space-y-4">
                <div class="rounded-xl border p-5"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl mb-3"
                         style="background-color: var(--gold-soft);">📍</div>
                    <h3 class="font-bold mb-1" style="color: var(--text-primary);">العنوان</h3>
                    <p class="text-[13px]" style="color: var(--text-secondary);">
                        فلسطين — غزة — شارع عمر المختار
                    </p>
                </div>

                <div class="rounded-xl border p-5"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl mb-3"
                         style="background-color: var(--gold-soft);">📞</div>
                    <h3 class="font-bold mb-1" style="color: var(--text-primary);">الهاتف</h3>
                    <p class="text-[13px]" style="color: var(--text-secondary);" dir="ltr">
                        +970 599 000 000
                    </p>
                </div>

                <div class="rounded-xl border p-5"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl mb-3"
                         style="background-color: var(--gold-soft);">✉️</div>
                    <h3 class="font-bold mb-1" style="color: var(--text-primary);">البريد الإلكتروني</h3>
                    <p class="text-[13px]" style="color: var(--text-secondary);">
                        info@clothing-store.com
                    </p>
                </div>

                <div class="rounded-xl border p-5"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xl mb-3"
                         style="background-color: var(--gold-soft);">⏰</div>
                    <h3 class="font-bold mb-2" style="color: var(--text-primary);">أوقات العمل</h3>
                    <p class="text-[13px]" style="color: var(--text-secondary);">
                        السبت — الخميس: 9ص — 10م
                    </p>
                    <p class="text-[13px]" style="color: var(--text-secondary);">
                        الجمعة: 2م — 10م
                    </p>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="lg:col-span-2">
                <div class="rounded-xl border p-6 md:p-8"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">

                    <h2 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">
                        أرسل لنا رسالة ✉️
                    </h2>

                    @if(session('success'))
                        <div class="rounded-lg p-4 mb-5 text-[13px]"
                             style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                            ✅ {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('pages.contact.send') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">الاسم *</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()?->full_name) }}" required
                                       class="form-input">
                                @error('name') <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="form-label">البريد الإلكتروني *</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                                       class="form-input">
                                @error('email') <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="form-label">الموضوع *</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required
                                   class="form-input">
                            @error('subject') <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">الرسالة *</label>
                            <textarea name="message" rows="6" required maxlength="2000"
                                      class="form-input py-3"
                                      style="height: auto; min-height: 150px;">{{ old('message') }}</textarea>
                            @error('message') <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                                class="w-full h-12 rounded-lg font-bold text-white text-[14px] transition hover:opacity-90"
                                style="background-color: var(--gold);">
                            📤 إرسال الرسالة
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection