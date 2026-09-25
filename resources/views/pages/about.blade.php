@extends('layouts.public')

@section('title', 'من نحن')

@section('content')

    {{-- Hero --}}
    <section style="background-color: var(--gold-soft);">
        <div class="max-w-[1400px] mx-auto px-4 py-16 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" style="color: var(--text-primary);">
                من نحن 👕
            </h1>
            <p class="text-lg max-w-2xl mx-auto" style="color: var(--text-secondary);">
                منصة تسوق عصرية تجمع أفضل المتاجر في مكان واحد
            </p>
        </div>
    </section>

    <div class="max-w-[1200px] mx-auto px-4 py-14">

        {{-- Story --}}
        <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <h2 class="text-3xl font-bold mb-4" style="color: var(--text-primary);">قصتنا</h2>
                <div class="space-y-4 text-[14px] leading-relaxed" style="color: var(--text-secondary);">
                    <p>
                        بدأت رحلتنا من فكرة بسيطة: <strong style="color: var(--text-primary);">تسهيل التسوق الإلكتروني</strong> وتوفير تجربة تسوق ممتعة وآمنة للجميع.
                    </p>
                    <p>
                        اليوم، نفتخر بوجود <strong style="color: var(--gold);">{{ \App\Models\Store::where('status', 'active')->count() }} متاجر</strong> معتمدة، وأكثر من <strong style="color: var(--gold);">{{ \App\Models\Product::where('status', 'active')->count() }} منتج</strong> متنوع، وآلاف العملاء السعداء.
                    </p>
                    <p>
                        نؤمن بأن كل زبون يستحق أفضل خدمة، لذلك نعمل باستمرار على تحسين منصتنا وإضافة ميزات جديدة.
                    </p>
                </div>
            </div>
            <div class="text-center text-[180px]">🛍️</div>
        </div>

        {{-- Values --}}
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center mb-8" style="color: var(--text-primary);">قيمنا</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-xl border p-8 text-center"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="text-5xl mb-4">🎯</div>
                    <h3 class="font-bold text-xl mb-2" style="color: var(--text-primary);">الجودة</h3>
                    <p class="text-[13px]" style="color: var(--text-secondary);">
                        نختار المنتجات بعناية ونضمن جودتها
                    </p>
                </div>
                <div class="rounded-xl border p-8 text-center"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="text-5xl mb-4">🤝</div>
                    <h3 class="font-bold text-xl mb-2" style="color: var(--text-primary);">الثقة</h3>
                    <p class="text-[13px]" style="color: var(--text-secondary);">
                        نبني علاقة طويلة الأمد مع عملائنا
                    </p>
                </div>
                <div class="rounded-xl border p-8 text-center"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="text-5xl mb-4">⚡</div>
                    <h3 class="font-bold text-xl mb-2" style="color: var(--text-primary);">السرعة</h3>
                    <p class="text-[13px]" style="color: var(--text-secondary);">
                        توصيل سريع وخدمة عملاء فورية
                    </p>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="rounded-2xl p-12 text-white mb-14"
             style="background-color: var(--gold);">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-5xl font-bold mb-2">{{ \App\Models\Store::where('status', 'active')->count() }}</div>
                    <div class="text-[13px] opacity-90">متجر معتمد</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">{{ \App\Models\Product::where('status', 'active')->count() }}</div>
                    <div class="text-[13px] opacity-90">منتج متنوع</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">{{ \App\Models\User::where('role', 'customer')->count() }}+</div>
                    <div class="text-[13px] opacity-90">زبون سعيد</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">{{ \App\Models\Order::count() }}+</div>
                    <div class="text-[13px] opacity-90">طلب مكتمل</div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="text-center">
            <h2 class="text-2xl font-bold mb-4" style="color: var(--text-primary);">جاهز للتسوق؟</h2>
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 h-12 px-8 rounded-lg font-bold text-white transition hover:opacity-90"
               style="background-color: var(--gold);">
                🛍️ ابدأ التسوق الآن
            </a>
        </div>
    </div>

@endsection