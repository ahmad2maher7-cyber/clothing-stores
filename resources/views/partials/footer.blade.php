<footer class="bg-forest-950 dark:bg-black text-cream mt-24">
    <div class="container-x py-16">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12 mb-14">

            {{-- ═══ About ═══ --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 flex items-center justify-center bg-gold-500 text-forest-950 rounded">
                        <i class="fa-solid fa-shirt"></i>
                    </div>
                    <div>
                        <h3 class="font-display text-lg font-bold text-white">متجر الملابس</h3>
                        <p class="text-[10px] tracking-widest uppercase text-gold-400/80">أزياء عصرية</p>
                    </div>
                </div>

                <p class="text-sm text-cream/60 leading-relaxed mb-6">
                    منصتك الأولى للأزياء العصرية. نقدم لك أفضل الملابس من متاجر موثوقة بأسعار منافسة.
                </p>

                <div class="flex gap-2">
                    @php
                        $socials = [
                            ['icon' => 'fa-brands fa-facebook-f', 'label' => 'Facebook'],
                            ['icon' => 'fa-brands fa-instagram', 'label' => 'Instagram'],
                            ['icon' => 'fa-brands fa-x-twitter', 'label' => 'Twitter'],
                            ['icon' => 'fa-brands fa-whatsapp', 'label' => 'WhatsApp'],
                        ];
                    @endphp
                    @foreach($socials as $social)
                        <a href="#" aria-label="{{ $social['label'] }}"
                           class="w-9 h-9 flex items-center justify-center bg-white/5 hover:bg-gold-500 hover:text-forest-950 text-cream/60 rounded transition-all">
                            <i class="{{ $social['icon'] }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- ═══ Quick Links ═══ --}}
            <div>
                <h4 class="text-xs font-semibold tracking-widest uppercase text-gold-400 mb-5">تسوّق</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('products.index') }}" class="text-cream/60 hover:text-gold-400 transition-colors">كل المنتجات</a></li>
                    <li><a href="{{ route('products.index', ['gender' => 'men']) }}" class="text-cream/60 hover:text-gold-400 transition-colors">ملابس رجالية</a></li>
                    <li><a href="{{ route('products.index', ['gender' => 'women']) }}" class="text-cream/60 hover:text-gold-400 transition-colors">ملابس نسائية</a></li>
                    <li><a href="{{ route('products.index', ['gender' => 'kids']) }}" class="text-cream/60 hover:text-gold-400 transition-colors">ملابس أطفال</a></li>
                    <li><a href="{{ route('offers.index') }}" class="text-cream/60 hover:text-gold-400 transition-colors">العروض والتخفيضات</a></li>
                </ul>
            </div>

            {{-- ═══ Support ═══ --}}
            <div>
                <h4 class="text-xs font-semibold tracking-widest uppercase text-gold-400 mb-5">الدعم</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('pages.about') }}" class="text-cream/60 hover:text-gold-400 transition-colors">من نحن</a></li>
                    <li><a href="{{ route('pages.contact') }}" class="text-cream/60 hover:text-gold-400 transition-colors">تواصل معنا</a></li>
                    <li><a href="{{ route('pages.faq') }}" class="text-cream/60 hover:text-gold-400 transition-colors">الأسئلة الشائعة</a></li>
                    <li><a href="#" class="text-cream/60 hover:text-gold-400 transition-colors">سياسة الخصوصية</a></li>
                    <li><a href="#" class="text-cream/60 hover:text-gold-400 transition-colors">سياسة الاستبدال والإرجاع</a></li>
                </ul>
            </div>

            {{-- ═══ Contact ═══ --}}
            <div>
                <h4 class="text-xs font-semibold tracking-widest uppercase text-gold-400 mb-5">تواصل معنا</h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot text-gold-400 mt-1 shrink-0 w-4"></i>
                        <span class="text-cream/60">فلسطين - غزة - شارع عمر المختار</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-gold-400 shrink-0 w-4"></i>
                        <span class="text-cream/60" dir="ltr">+970 599 000 000</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-gold-400 shrink-0 w-4"></i>
                        <span class="text-cream/60">info@clothing-store.com</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-clock text-gold-400 shrink-0 w-4"></i>
                        <span class="text-cream/60">السبت - الخميس: 9ص - 10م</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- ═══ Payment Methods ═══ --}}
        <div class="border-t border-white/10 pt-8 pb-6 flex flex-wrap items-center justify-center gap-4">
            <span class="text-xs tracking-widest uppercase text-cream/40">طرق الدفع:</span>
            <div class="flex gap-2">
                @php
                    $payments = [
                        ['label' => 'VISA', 'icon' => 'fa-brands fa-cc-visa'],
                        ['label' => 'MC', 'icon' => 'fa-brands fa-cc-mastercard'],
                        ['label' => 'PAYPAL', 'icon' => 'fa-brands fa-cc-paypal'],
                        ['label' => 'COD', 'icon' => 'fa-solid fa-money-bill-wave'],
                    ];
                @endphp
                @foreach($payments as $method)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] tracking-widest font-bold text-cream/60 border border-white/10 rounded">
                        <i class="{{ $method['icon'] }} text-base"></i>
                        {{ $method['label'] }}
                    </span>
                @endforeach
            </div>
        </div>

        {{-- ═══ Copyright ═══ --}}
        <div class="border-t border-white/10 pt-6 flex flex-col md:flex-row items-center justify-between gap-3 text-xs text-cream/40">
            <p>© {{ date('Y') }} متجر الملابس — جميع الحقوق محفوظة.</p>
            <p class="flex items-center gap-1.5">
                صُنع بـ 
                <i class="fa-solid fa-heart text-red-500"></i>
                في فلسطين
            </p>
        </div>
    </div>
</footer>