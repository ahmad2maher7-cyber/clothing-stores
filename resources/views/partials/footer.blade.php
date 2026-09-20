<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 py-12">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">

            {{-- About --}}
            <div>
                <div class="flex items-center space-x-2 space-x-reverse mb-4">
                    <span class="text-3xl">👕</span>
                    <h3 class="text-xl font-bold text-white">متجر الملابس</h3>
                </div>
                <p class="text-sm leading-relaxed mb-4">
                    منصتك الأولى للأزياء العصرية. نقدم لك أفضل الملابس من متاجر موثوقة بأسعار منافسة.
                </p>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-indigo-600 rounded-full flex items-center justify-center transition">📘</a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-pink-600 rounded-full flex items-center justify-center transition">📷</a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-blue-500 rounded-full flex items-center justify-center transition">🐦</a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-green-600 rounded-full flex items-center justify-center transition">💬</a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-white font-bold mb-4">روابط سريعة</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-indigo-400 transition">الرئيسية</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-indigo-400 transition">كل المنتجات</a></li>
                    <li><a href="{{ route('stores.index') }}" class="hover:text-indigo-400 transition">المتاجر</a></li>
                    <li><a href="{{ route('offers.index') }}" class="hover:text-indigo-400 transition">العروض</a></li>
                </ul>
            </div>

            {{-- Customer Service --}}
<div>
    <h4 class="text-white font-bold mb-4">روابط مهمة</h4>
    <ul class="space-y-2 text-sm">
        <li><a href="{{ route('pages.about') }}" class="hover:text-indigo-400 transition">من نحن</a></li>
        <li><a href="{{ route('pages.contact') }}" class="hover:text-indigo-400 transition">تواصل معنا</a></li>
        <li><a href="{{ route('pages.faq') }}" class="hover:text-indigo-400 transition">الأسئلة الشائعة</a></li>
        <li><a href="{{ route('products.index') }}" class="hover:text-indigo-400 transition">كل المنتجات</a></li>
    </ul>
</div>

            {{-- Contact --}}
            <div>
                <h4 class="text-white font-bold mb-4">تواصل معنا</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start space-x-2 space-x-reverse">
                        <span>📍</span>
                        <span>فلسطين - غزة</span>
                    </li>
                    <li class="flex items-center space-x-2 space-x-reverse">
                        <span>📞</span>
                        <span>+970 599 000 000</span>
                    </li>
                    <li class="flex items-center space-x-2 space-x-reverse">
                        <span>✉️</span>
                        <span>info@clothing-store.com</span>
                    </li>
                    <li class="flex items-center space-x-2 space-x-reverse">
                        <span>⏰</span>
                        <span>السبت - الخميس: 9ص - 10م</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Payment Methods --}}
        <div class="border-t border-gray-800 pt-6 pb-4">
            <div class="flex flex-wrap justify-center items-center gap-4 mb-4">
                <span class="text-sm text-gray-400">طرق الدفع:</span>
                <div class="flex space-x-3 space-x-reverse">
                    <span class="bg-gray-800 px-3 py-1 rounded text-sm">💳 Visa</span>
                    <span class="bg-gray-800 px-3 py-1 rounded text-sm">💳 MasterCard</span>
                    <span class="bg-gray-800 px-3 py-1 rounded text-sm">💰 عند الاستلام</span>
                    <span class="bg-gray-800 px-3 py-1 rounded text-sm">📱 محفظة إلكترونية</span>
                </div>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-gray-800 pt-4 text-center text-sm">
            <p>© {{ date('Y') }} متجر الملابس — جميع الحقوق محفوظة.</p>
            <p class="mt-1 text-xs text-gray-500">صُنع بـ ❤️ في فلسطين</p>
        </div>
    </div>
</footer>
