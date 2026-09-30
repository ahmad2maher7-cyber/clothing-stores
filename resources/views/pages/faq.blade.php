@extends('layouts.public')

@section('title', 'الأسئلة الشائعة')

@section('content')

    {{-- ═══ Hero ═══ --}}
    <section class="bg-stone-50 dark:bg-zinc-900/50 border-b border-stone-200 dark:border-stone-800">
        <div class="container-x py-16 text-center">
            <span class="eyebrow block mb-3">— مساعدة</span>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-ink dark:text-cream mb-4 tracking-tight">
                الأسئلة الشائعة
            </h1>
            <p class="text-base text-ink-muted dark:text-cream/60">
                إجابات على أكثر الأسئلة تكراراً
            </p>
        </div>
    </section>

    <div class="container-x py-16">
        <div class="max-w-3xl mx-auto">

            {{-- ═══ FAQ List ═══ --}}
            <div class="space-y-3" x-data="{ open: null }">

                @php
                    $faqs = [
                        ['q' => 'كيف أضيف منتجاً إلى السلة؟', 'a' => 'تصفح المنتجات، اختر المنتج المطلوب، حدد المقاس واللون والكمية، ثم اضغط على زر "أضف للسلة". ستجد المنتج في سلة التسوق أعلى الصفحة.'],
                        ['q' => 'ما هي طرق الدفع المتاحة؟', 'a' => 'نوفر عدة طرق للدفع: الدفع عند الاستلام، البطاقات البنكية (Visa, MasterCard)، المحافظ الإلكترونية، والتقسيط. يمكنك اختيار الطريقة المناسبة عند إتمام الطلب.'],
                        ['q' => 'كم تستغرق مدة التوصيل؟', 'a' => 'مدة التوصيل تعتمد على منطقتك. عادة من 1 إلى 3 أيام عمل داخل غزة، ومن 2 إلى 5 أيام لبقية المناطق. ستظهر المدة التقديرية عند اختيار منطقة الشحن.'],
                        ['q' => 'هل يمكنني إرجاع منتج؟', 'a' => 'نعم، يمكنك إرجاع أو استبدال المنتج خلال 14 يوماً من الاستلام بشرط أن يكون المنتج بحالته الأصلية مع التغليف. تختلف السياسة من متجر لآخر، راجع سياسة كل متجر.'],
                        ['q' => 'كيف أستخدم كود الخصم؟', 'a' => 'أثناء إتمام الطلب، ستجد حقل "كود الخصم" في ملخص الفاتورة. أدخل الكود واضغط "تطبيق" ليُخصم المبلغ تلقائياً من إجمالي الطلب.'],
                        ['q' => 'كيف أضيف منتجاً للمفضلة؟', 'a' => 'في صفحة المنتج أو في قائمة المنتجات، اضغط على أيقونة القلب. ستُحفظ المنتجات في قسم "المفضلة" في حسابك للرجوع إليها لاحقاً.'],
                        ['q' => 'هل يمكنني الشراء بدون تسجيل؟', 'a' => 'يمكنك تصفح المنتجات بدون تسجيل، لكن لإتمام الطلب يجب إنشاء حساب. التسجيل مجاني وسريع، ويتيح لك تتبع طلباتك وحفظ مفضلاتك.'],
                        ['q' => 'كيف أُقيّم منتجاً اشتريته؟', 'a' => 'بعد استلام الطلب، اذهب إلى "طلباتي" واختر الطلب المُستلم، ثم اضغط "قيّم الطلب". يمكنك إضافة تقييم بالنجوم مع تعليق وصور.'],
                        ['q' => 'كيف أصبح تاجراً على المنصة؟', 'a' => 'اضغط على "إنشاء حساب" واختر "تاجر". بعد التسجيل، ستحتاج لإنشاء متجرك وإرساله للمراجعة. بعد الموافقة، يمكنك إضافة منتجاتك.'],
                        ['q' => 'هل بياناتي آمنة؟', 'a' => 'نعم، نستخدم أحدث تقنيات التشفير لحماية بياناتك. لا نشارك بياناتك مع أي طرف ثالث، ونحترم خصوصيتك بشكل كامل.'],
                    ];
                @endphp

                @foreach($faqs as $index => $faq)
                    <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg overflow-hidden hover:border-forest-500 dark:hover:border-gold-500 transition-colors">
                        <button @click="open = open === {{ $index }} ? null : {{ $index }}"
                                class="w-full flex justify-between items-center gap-4 p-5 text-right hover:bg-stone-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <span class="font-medium text-sm text-ink dark:text-cream">{{ $faq['q'] }}</span>
                            <span class="w-6 h-6 flex items-center justify-center text-forest-700 dark:text-gold-400 transition-transform duration-200 shrink-0"
                                  :class="open === {{ $index }} ? 'rotate-45' : ''">
                                <i class="fa-solid fa-plus text-base"></i>
                            </span>
                        </button>
                        <div x-show="open === {{ $index }}" x-collapse x-cloak
                             class="px-5 pb-5 text-sm text-ink-soft dark:text-cream/70 leading-relaxed border-t border-stone-100 dark:border-stone-800 pt-4">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ═══ More Help ═══ --}}
            <div class="mt-12 bg-forest-900 dark:bg-forest-950 rounded-lg p-10 text-center text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative">
                    <div class="w-16 h-16 mx-auto mb-5 flex items-center justify-center bg-white/10 backdrop-blur rounded-2xl">
                        <i class="fa-solid fa-circle-question text-gold-400 text-3xl"></i>
                    </div>
                    <h2 class="font-display text-2xl font-bold mb-3">
                        لم تجد إجابتك؟
                    </h2>
                    <p class="text-cream/70 mb-6 max-w-md mx-auto">
                        فريق الدعم جاهز لمساعدتك في أي وقت
                    </p>
                    <a href="{{ route('pages.contact') }}" class="inline-flex items-center justify-center gap-2 h-12 px-8 font-semibold text-xs tracking-widest uppercase bg-gold-500 text-forest-950 hover:bg-gold-400 rounded transition-colors">
                        <i class="fa-solid fa-headset"></i>
                        تواصل معنا
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush