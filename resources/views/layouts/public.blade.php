<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'متجر الملابس') — أفضل الملابس بأفضل الأسعار</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    {{-- الخطوط --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Cairo', sans-serif; }
        [x-cloak] { display: none !important; }
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        (function () {
            const saved = localStorage.getItem('darkMode');
            if (saved === 'true') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @stack('styles')
</head>

<body class="antialiased min-h-screen flex flex-col">

    {{-- Header --}}
    @include('partials.header')

    {{-- Mobile Menu --}}
    @include('partials.mobile-menu')

    {{-- Main Content --}}
    <main class="flex-1">
        {{-- رسائل النجاح --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="fixed top-24 left-1/2 -translate-x-1/2 z-50 text-white px-6 py-3 rounded-lg shadow-lg text-[13px] font-medium"
                 style="background-color: #22c55e;">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="fixed top-24 left-1/2 -translate-x-1/2 z-50 text-white px-6 py-3 rounded-lg shadow-lg text-[13px] font-medium"
                 style="background-color: #dc2626;">
                ❌ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Toast Container --}}
    <div id="toast-container" class="fixed bottom-6 left-6 z-[100] space-y-2"></div>

    {{-- Scripts --}}
    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            } else {
                html.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const colors = {
                success: '#22c55e',
                error: '#dc2626',
                info: '#3b82f6',
            };
            const toast = document.createElement('div');
            toast.className = 'text-white px-5 py-3 rounded-lg shadow-lg text-[13px] font-medium transition-all duration-300 opacity-0 translate-y-2';
            toast.style.backgroundColor = colors[type] || colors.success;
            toast.textContent = message;
            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-y-2');
            });
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 2500);
        }

        async function toggleWishlist(productId) {
            @auth
                try {
                    const res = await fetch(`/customer/wishlist/toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ product_id: productId }),
                    });
                    const data = await res.json();
                    showToast(data.message, data.status === 'added' ? 'success' : 'info');
                } catch (e) {
                    showToast('حدث خطأ', 'error');
                }
            @else
                window.location.href = '{{ route('login') }}';
            @endauth
        }
    </script>

    @stack('scripts')
</body>

</html>