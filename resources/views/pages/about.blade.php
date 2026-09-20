@extends('layouts.public')

@section('title', 'من نحن')

@section('content')

    {{-- Hero --}}
    <section class="bg-gradient-to-l from-indigo-700 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 py-16 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">من نحن 👕</h1>
            <p class="text-lg text-indigo-100 max-w-2xl mx-auto">
                منصة تسوق عصرية تجمع أفضل المتاجر في مكان واحد
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-16">

        {{-- Story --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">قصتنا</h2>
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>
                        بدأت رحلتنا من فكرة بسيطة: <strong>تسهيل التسوق الإلكتروني</strong> وتوفير تجربة تسوق ممتعة وآمنة للجميع.
                    </p>
                    <p>
                        اليوم، نفتخر بوجود <strong>3 متاجر</strong> معتمدة، وأكثر من <strong>45 منتجاً</strong> متنوعاً، وآلاف العملاء السعداء.
                    </p>
                    <p>
                        نؤمن بأن كل زبون يستحق أفضل خدمة، لذلك نعمل باستمرار على تحسين منصتنا وإضافة ميزات جديدة.
                    </p>
                </div>
            </div>
            <div class="text-center text-[200px]">🛍️</div>
        </div>

        {{-- Values --}}
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">قيمنا</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow p-8 text-center">
                    <div class="text-5xl mb-4">🎯</div>
                    <h3 class="font-bold text-xl mb-2">الجودة</h3>
                    <p class="text-gray-600 text-sm">نختار المنتجات بعناية ونضمن جودتها</p>
                </div>
                <div class="bg-white rounded-xl shadow p-8 text-center">
                    <div class="text-5xl mb-4">🤝</div>
                    <h3 class="font-bold text-xl mb-2">الثقة</h3>
                    <p class="text-gray-600 text-sm">نبني علاقة طويلة الأمد مع عملائنا</p>
                </div>
                <div class="bg-white rounded-xl shadow p-8 text-center">
                    <div class="text-5xl mb-4">⚡</div>
                    <h3 class="font-bold text-xl mb-2">السرعة</h3>
                    <p class="text-gray-600 text-sm">توصيل سريع وخدمة عملاء فورية</p>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="bg-gradient-to-l from-indigo-600 to-purple-700 rounded-2xl p-12 text-white">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-5xl font-bold mb-2">{{ \App\Models\Store::where('status', 'active')->count() }}</div>
                    <div class="text-indigo-100">متجر معتمد</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">{{ \App\Models\Product::where('status', 'active')->count() }}</div>
                    <div class="text-indigo-100">منتج متنوع</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">{{ \App\Models\User::where('role', 'customer')->count() }}+</div>
                    <div class="text-indigo-100">زبون سعيد</div>
                </div>
                <div>
                    <div class="text-5xl font-bold mb-2">{{ \App\Models\Order::count() }}+</div>
                    <div class="text-indigo-100">طلب مكتمل</div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="text-center mt-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">جاهز للتسوق؟</h2>
            <a href="{{ route('products.index') }}"
               class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-lg font-bold text-lg transition">
                🛍️ ابدأ التسوق الآن
            </a>
        </div>
    </div>

@endsection
