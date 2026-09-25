<footer class="bg-white border-t border-gray-200 mt-20">
    <div class="max-w-7xl mx-auto px-4 py-12">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">

            {{-- About --}}
            <div class="md:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-2xl">👕</span>
                    <h3 class="text-lg font-bold text-gray-900">متجر الملابس</h3>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed mb-4">
                    منصتك الأولى للأزياء العصرية. نقدم لك أفضل الملابس من متاجر موثوقة بأسعار منافسة.
                </p>
                <div class="flex gap-2">
                    <a href="#" class="w-9 h-9 border border-gray-300 rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:border-gray-400 transition">
                        📘
                    </a>
                    <a href="#" class="w-9 h-9 border border-gray-300 rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:border-gray-400 transition">
                        📷
                    </a>
                    <a href="#" class="w-9 h-9 border border-gray-300 rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:border-gray-400 transition">
                        🐦
                    </a>
                    <a href="#" class="w-9 h-9 border border-gray-300 rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:border-gray-400 transition">
                        💬
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-sm font-bold text-gray-900 mb-4">روابط سريعة</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 transition">الرئيسية</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900 transition">كل المنتجات</a></li>
                    <li><a href="{{ route('stores.index') }}" class="text-gray-600 hover:text-gray-900 transition">المتاجر</a></li>
                    <li><a href="{{ route('offers.index') }}" class="text-gray-600 hover:text-gray-900 transition">العروض</a></li>
                </ul>
            </div>

            {{-- Support --}}
            <div>
                <h4 class="text-sm font-bold text-gray-900 mb-4">الدعم</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('pages.about') }}" class="text-gray-600 hover:text-gray-900 transition">من نحن</a></li>
                    <li><a href="{{ route('pages.contact') }}" class="text-gray-600 hover:text-gray-900 transition">تواصل معنا</a></li>
                    <li><a href="{{ route('pages.faq') }}" class="text-gray-600 hover:text-gray-900 transition">الأسئلة الشائعة</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-gray-900 transition">سياسة الخصوصية</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-sm font-bold text-gray-900 mb-4">تواصل معنا</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2">
                        <span class="text-gray-400">📍</span>
                        <span class="text-gray-600">فلسطين - غزة</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-gray-400">📞</span>
                        <span class="text-gray-600" dir="ltr">+970 599 000 000</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-gray-400">✉️</span>
                        <span class="text-gray-600">info@clothing-store.com</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="pt-6 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-3">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} متجر الملابس — جميع الحقوق محفوظة.
            </p>
            <div class="flex items-center gap-3 text-xs text-gray-500">
                <span>طرق الدفع:</span>
                <span>💳</span>
                <span>💰</span>
                <span>📱</span>
            </div>
        </div>
    </div>
</footer>