@extends('layouts.public')

@section('title', 'من نحن')

@section('content')

    {{-- ═══ Hero ═══ --}}
    <section class="bg-stone-50 dark:bg-zinc-900/50 border-b border-stone-200 dark:border-stone-800">
        <div class="container-x py-16 text-center">
            <span class="eyebrow block mb-3">— تعرّف علينا</span>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-ink dark:text-cream mb-4 tracking-tight">
                من نحن
            </h1>
            <p class="text-base text-ink-muted dark:text-cream/60 max-w-2xl mx-auto">
                منصة تسوق عصرية تجمع أفضل المتاجر في مكان واحد
            </p>
        </div>
    </section>

    <div class="container-x py-16">

        {{-- ═══ Story ═══ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-20">
            <div>
                <span class="eyebrow block mb-3">— قصتنا</span>
                <h2 class="font-display text-3xl font-bold text-ink dark:text-cream mb-6">
                    نبدأ من فكرة... ونصل إلى منصة
                </h2>
                <div class="space-y-4 text-ink-soft dark:text-cream/70 leading-relaxed">
                    <p>
                        بدأت رحلتنا من فكرة بسيطة: 
                        <strong class="text-ink dark:text-cream">تسهيل التسوق الإلكتروني</strong> 
                        وتوفير تجربة تسوق ممتعة وآمنة للجميع.
                    </p>
                    <p>
                        اليوم، نفتخر بوجود 
                        <strong class="text-forest-700 dark:text-gold-400">{{ \App\Models\Store::where('status', 'active')->count() }} متاجر</strong> 
                        معتمدة، وأكثر من 
                        <strong class="text-forest-700 dark:text-gold-400">{{ \App\Models\Product::where('status', 'active')->count() }} منتجاً</strong> 
                        متنوعاً.
                    </p>
                    <p>
                        نؤمن بأن كل زبون يستحق أفضل خدمة، لذلك نعمل باستمرار على تحسين منصتنا.
                    </p>
                </div>
            </div>

            {{-- Visual --}}
            <div class="relative">
                <div class="aspect-square rounded-2xl bg-gradient-to-br from-forest-100 to-gold-100 dark:from-forest-950 dark:to-gold-950/30 border border-stone-200 dark:border-stone-800 flex items-center justify-center">
                    <i class="fa-solid fa-shirt text-forest-700 dark:text-gold-400" style="font-size: 8rem;"></i>
                </div>
            </div>
        </div>

        {{-- ═══ Values ═══ --}}
        <div class="mb-20">
            <div class="text-center mb-12">
                <span class="eyebrow block mb-3">— قيمنا</span>
                <h2 class="font-display text-3xl font-bold text-ink dark:text-cream">
                    ما نؤمن به
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $values = [
                        ['icon' => 'fa-shield-halved', 'title' => 'الجودة',   'desc' => 'نختار المنتجات بعناية ونضمن جودتها'],
                        ['icon' => 'fa-handshake',     'title' => 'الثقة',   'desc' => 'نبني علاقة طويلة الأمد مع عملائنا'],
                        ['icon' => 'fa-bolt',          'title' => 'السرعة',  'desc' => 'توصيل سريع وخدمة عملاء فورية'],
                    ];
                @endphp

                @foreach($values as $value)
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg p-8 text-center hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                        <div class="w-16 h-16 mx-auto mb-5 flex items-center justify-center bg-forest-50 dark:bg-forest-950/40 text-forest-700 dark:text-gold-400 rounded-full">
                            <i class="fa-solid {{ $value['icon'] }} text-3xl"></i>
                        </div>
                        <h3 class="font-display text-xl font-bold text-ink dark:text-cream mb-2">{{ $value['title'] }}</h3>
                        <p class="text-sm text-ink-muted dark:text-cream/60">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ═══ Stats ═══ --}}
        <div class="bg-forest-900 dark:bg-forest-950 rounded-lg p-10 text-white mb-20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <p class="font-display text-4xl md:text-5xl font-bold text-gold-400 mb-2">
                        {{ \App\Models\Store::where('status', 'active')->count() }}
                    </p>
                    <p class="text-xs tracking-widest uppercase text-cream/60">متجر معتمد</p>
                </div>
                <div>
                    <p class="font-display text-4xl md:text-5xl font-bold text-gold-400 mb-2">
                        {{ \App\Models\Product::where('status', 'active')->count() }}
                    </p>
                    <p class="text-xs tracking-widest uppercase text-cream/60">منتج متنوع</p>
                </div>
                <div>
                    <p class="font-display text-4xl md:text-5xl font-bold text-gold-400 mb-2">
                        {{ \App\Models\User::where('role', 'customer')->count() }}+
                    </p>
                    <p class="text-xs tracking-widest uppercase text-cream/60">زبون سعيد</p>
                </div>
                <div>
                    <p class="font-display text-4xl md:text-5xl font-bold text-gold-400 mb-2">
                        {{ \App\Models\Order::count() }}+
                    </p>
                    <p class="text-xs tracking-widest uppercase text-cream/60">طلب مكتمل</p>
                </div>
            </div>
        </div>

        {{-- ═══ CTA ═══ --}}
        <div class="text-center">
            <span class="eyebrow block mb-4">— ابدأ الآن</span>
            <h2 class="font-display text-3xl font-bold text-ink dark:text-cream mb-6">
                جاهز للتسوّق؟
            </h2>
            <a href="{{ route('products.index') }}" class="btn-solid">
                <i class="fa-solid fa-bag-shopping"></i>
                ابدأ التسوق
            </a>
        </div>
    </div>

@endsection