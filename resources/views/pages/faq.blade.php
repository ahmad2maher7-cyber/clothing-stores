@extends('layouts.public')

@section('title', 'الأسئلة الشائعة')

@section('content')

    <section class="bg-gradient-to-l from-indigo-700 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 py-16 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">الأسئلة الشائعة ❓</h1>
            <p class="text-lg text-indigo-100">إجابات على أكثر الأسئلة تكراراً</p>
        </div>
    </section>

    <div class="max-w-3xl mx-auto px-4 py-16">

        <div class="space-y-3" x-data="{ open: null }">

            @php
                $faqs = [
                    ['q' => '🛒 كيف أضيف منتجاً إلى السلة؟', 'a' => 'تصفح المنتجات، اختر المنتج المطلوب، حدد المقاس واللون والكمية، ثم اضغط على زر "أضف للسلة". ستجد المنتج في سلة التسوق أعلى الصفحة.'],
                    ['q' => '💰 ما هي طرق الدفع المتاحة؟', 'a' => 'نوفر عدة طرق للدفع: الدفع عند الاستلام، البطاقات البنكية (Visa, MasterCard)، المحافظ الإلكترونية، والتقسيط. يمكنك اختيار الطريقة المناسبة عند إتمام الطلب.'],
                    ['q' => '🚚 كم تستغرق مدة التوصيل؟', 'a' => 'مدة التوصيل تعتمد على منطقتك. عادة من 1 إلى 3 أيام عمل داخل غزة، ومن 2 إلى 5 أيام لبقية المناطق. ستظهر المدة التقديرية عند اختيار منطقة الشحن.'],
                    ['q' => '🔄 هل يمكنني إرجاع منتج؟', 'a' => 'نعم، يمكنك إرجاع أو استبدال المنتج خلال 14 يوماً من الاستلام بشرط أن يكون المنتج بحالته الأصلية مع التغليف. تختلف السياسة من متجر لآخر، راجع سياسة كل متجر.'],
                    ['q' => '🏷️ كيف أستخدم كود الخصم؟', 'a' => 'أثناء إتمام الطلب، ستجد حقل "كود الخصم" في ملخص الفاتورة. أدخل الكود واضغط "تطبيق" ليُخصم المبلغ تلقائياً من إجمالي الطلب.'],
                    ['q' => '❤️ كيف أضيف منتجاً للمفضلة؟', 'a' => 'في صفحة المنتج أو في قائمة المنتجات، اضغط على أيقونة القلب ❤️. ستُحفظ المنتجات في قسم "المفضلة" في حسابك للرجوع إليها لاحقاً.'],
                    ['q' => '👤 هل يمكنني الشراء بدون تسجيل؟', 'a' => 'يمكنك تصفح المنتجات بدون تسجيل، لكن لإتمام الطلب يجب إنشاء حساب. التسجيل مجاني وسريع، ويتيح لك تتبع طلباتك وحفظ مفضلاتك.'],
                    ['q' => '⭐ كيف أُقيّم منتجاً اشتريته؟', 'a' => 'بعد استلام الطلب، اذهب إلى "طلباتي" واختر الطلب المُستلم، ثم اضغط "قيّم الطلب". يمكنك إضافة تقييم بالنجوم مع تعليق وصور.'],
                    ['q' => '🏪 كيف أصبح تاجراً على المنصة؟', 'a' => 'اضغط على "كن تاجراً" في أعلى الصفحة، وسجّل حساباً جديداً كتاجر. بعد الموافقة، يمكنك إضافة متجرك ومنتجاتك بسهولة.'],
                    ['q' => '🔒 هل بياناتي آمنة؟', 'a' => 'نعم، نستخدم أحدث تقنيات التشفير لحماية بياناتك. لا نشارك بياناتك مع أي طرف ثالث، ونحترم خصوصيتك بشكل كامل.'],
                ];
            @endphp

            @foreach($faqs as $index => $faq)
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <button @click="open = open === {{ $index }} ? null : {{ $index }}"
                            class="w-full flex justify-between items-center p-5 text-right hover:bg-gray-50 transition">
                        <span class="font-medium text-gray-800">{{ $faq['q'] }}</span>
                        <span class="text-indigo-600 text-2xl transition-transform"
                              :class="open === {{ $index }} ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="open === {{ $index }}" x-collapse x-cloak
                         class="px-5 pb-5 text-gray-600 text-sm leading-relaxed border-t">
                        {{ $faq['a'] }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- More Help --}}
        <div class="mt-12 bg-indigo-50 rounded-xl p-8 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-3">لم تجد إجابتك؟ 🤔</h2>
            <p class="text-gray-600 mb-6">فريق الدعم جاهز لمساعدتك في أي وقت</p>
            <a href="{{ route('pages.contact') }}"
               class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-bold transition">
                📞 تواصل معنا
            </a>
        </div>
    </div>

@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush