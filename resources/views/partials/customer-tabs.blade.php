<div class="bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex overflow-x-auto">
            {{-- Dashboard --}}
            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center px-5 py-4 border-b-2 whitespace-nowrap transition
                      {{ request()->routeIs('customer.dashboard') ? 'border-indigo-600 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600' }}">
                <span class="text-lg ml-2">📊</span>
                <span>لوحة التحكم</span>
            </a>

            {{-- Orders --}}
            <a href="{{ route('customer.orders.index') }}"
               class="flex items-center px-5 py-4 border-b-2 whitespace-nowrap transition
                      {{ request()->routeIs('customer.orders.*') ? 'border-indigo-600 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600' }}">
                <span class="text-lg ml-2">📦</span>
                <span>طلباتي</span>
                @php
                    $ordersCount = auth()->user()->orders()->whereIn('status', ['pending', 'processing', 'shipped', 'delivering'])->count();
                @endphp
                @if($ordersCount > 0)
                    <span class="mr-2 bg-indigo-600 text-white text-xs px-2 py-0.5 rounded-full">{{ $ordersCount }}</span>
                @endif
            </a>

            {{-- Wishlist --}}
            <a href="{{ route('customer.wishlist') }}"
               class="flex items-center px-5 py-4 border-b-2 whitespace-nowrap transition
                      {{ request()->routeIs('customer.wishlist') ? 'border-indigo-600 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600' }}">
                <span class="text-lg ml-2">❤️</span>
                <span>المفضلة</span>
                @php
                    $wishlistCount = auth()->user()->wishlists()->count();
                @endphp
                @if($wishlistCount > 0)
                    <span class="mr-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $wishlistCount }}</span>
                @endif
            </a>

            {{-- Reviews --}}
            <a href="#"
               class="flex items-center px-5 py-4 border-b-2 whitespace-nowrap transition
                      {{ request()->routeIs('customer.reviews.*') ? 'border-indigo-600 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600' }}">
                <span class="text-lg ml-2">⭐</span>
                <span>تقييماتي</span>
            </a>

            {{-- Profile --}}
            <a href="{{ route('customer.profile') }}"
               class="flex items-center px-5 py-4 border-b-2 whitespace-nowrap transition
                      {{ request()->routeIs('customer.profile') ? 'border-indigo-600 text-indigo-600 font-medium' : 'border-transparent text-gray-600 hover:text-indigo-600' }}">
                <span class="text-lg ml-2">👤</span>
                <span>الملف الشخصي</span>
            </a>
        </div>
    </div>
</div>
