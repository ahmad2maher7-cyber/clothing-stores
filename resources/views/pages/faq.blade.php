@extends('layouts.public')

@section('title', 'الأسئلة الشائعة')

@section('content')

    <section style="background-color: var(--gold-soft);">
        <div class="max-w-[1400px] mx-auto px-4 py-16 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4" style="color: var(--text-primary);">
                الأسئلة الشائعة ❓
            </h1>
            <p class="text-lg" style="color: var(--text-secondary);">
                إجابات على أكثر الأسئلة تكراراً
            </p>
        </div>
    </section>

    <div class="max-w-3xl mx-auto px-4 py-14">

        <div class="space-y-3" x-data="{ open: null }">

            @php
                $faqs = [
                    ['q' => '🛒 كيف أضيف منتجاً إلى السلة؟', 'a' => 'تصفح المنتجات، اختر المنتج المطلوب، حدد المقاس واللون والكمية، ثم اضغط على زر "أضف للسلة". ستجد المنتج في سلة التسوق أعلى الصفحة.'],
                    ['q' => '💰 ما هي طرق الدفع المتاحة؟', 'a' => 'نوفر عدة طرق للدفع: الدفع عند الاستلام، البطاقات البنكية (Visa, MasterCard)، المحافظ الإلكترونية، والتقسيط. يمكنك اختيار الطريقة المناسبة عند إتمام الطلب.'],
                    ['q' => '🚚 كم تستغرق مدة التوصيل؟', 'a' => 'مدة التوصيل تعتمد على منطقتك. عادة من 1 إلى 3 أيام عمل داخل غزة، ومن 2 إلى 5 أيام لبقية المناطق. ستظهر المدة التقديرية عند اختيار منطقة الشحن.'],
                    ['q' => '🔄 هل يمكنني إرجاع منتج؟', 'a' => 'نعم، يمكنك إرجاع أو استبدال المنتج خلال 14 يوماً من الاستلام بشرط أن يكون المنتج بحالته الأصلية مع التغليف.'],
                    ['q' => '🏷️ كيف أستخدم كود الخصم؟', 'a' => 'أثناء إتمام الطلب، ستجد حقل "كود الخصم" في ملخص الفاتورة. أدخل الكود واضغط "تطبيق" ليُخصم المبلغ تلقائياً من إجمالي الطلب.'],
                    ['q' => '❤️ كيف أضيف منتجاً للمفضلة؟', 'a' => 'في صفحة المنتج أو في قائمة المنتجات، اضغط على أيقونة القلب ❤️. ستُحفظ المنتجات في قسم "المفضلة" في حسابك للرجوع إليها لاحقاً.'],
                    ['q' => '👤 هل يمكنني الشراء بدون تسجيل؟', 'a' => 'يمكنك تصفح المنتجات بدون تسجيل، لكن لإتمام الطلب يجب إنشاء حساب. التسجيل مجاني وسريع، ويتيح لك تتبع طلباتك وحفظ مفضلاتك.'],
                    ['q' => '⭐ كيف أُقيّم منتجاً اشتريته؟', 'a' => 'بعد استلام الطلب، اذهب إلى "طلباتي" واختر الطلب المُستلم، ثم اضغط "قيّم الطلب". يمكنك إضافة تقييم بالنجوم مع تعليق وصور.'],
                    ['q' => '🏪 كيف أصبح تاجراً على المنصة؟', 'a' => 'اضغط على "كن تاجراً" في أعلى الصفحة، وسجّل حساباً جديداً كتاجر. بعد الموافقة، يمكنك إضافة متجرك ومنتجاتك بسهولة.'],
                    ['q' => '🔒 هل بياناتي آمنة؟', 'a' => 'نعم، نستخدم أحدث تقنيات التشفير لحماية بياناتك. لا نشارك بياناتك مع أي طرف ثالث، ونحترم خصوصيتك بشكل كامل.'],
                ];
            @endphp

            @foreach($faqs as $index => $faq)
                <div class="rounded-xl border overflow-hidden"
                     style="background-color: var(--bg-primary); border-color: var(--border-light);">

                    <button @click="open = open === {{ $index }} ? null : {{ $index }}"
                            class="w-full flex justify-between items-center p-5 text-right transition"
                            onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                            onmouseout="this.style.backgroundColor='transparent';">
                        <span class="text-[14px] font-semibold" style="color: var(--text-primary);">
                            {{ $faq['q'] }}
                        </span>
                        <span class="text-2xl transition-transform" style="color: var(--gold);"
                              :class="open === {{ $index }} ? 'rotate-45' : ''">+</span>
                    </button>

                    <div x-show="open === {{ $index }}" x-cloak x-collapse
                         class="px-5 pb-5 text-[13px] leading-relaxed border-t"
                         style="color: var(--text-secondary); border-color: var(--border-light);">
                        <div class="pt-4">{{ $faq['a'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- More Help --}}
        <div class="mt-12 rounded-xl p-8 text-center border"
             style="background-color: var(--gold-soft); border-color: var(--border-light);">
            <h2 class="text-2xl font-bold mb-3" style="color: var(--text-primary);">
                لم تجد إجابتك؟ 🤔
            </h2>
            <p class="text-[13px] mb-6" style="color: var(--text-secondary);">
                فريق الدعم جاهز لمساعدتك في أي وقت
            </p>
            <a href="{{ route('pages.contact') }}"
               class="inline-flex items-center gap-2 h-11 px-6 rounded-lg font-bold text-white text-[13px] transition hover:opacity-90"
               style="background-color: var(--gold);">
                📞 تواصل معنا
            </a>
        </div>
    </div>

@endsection