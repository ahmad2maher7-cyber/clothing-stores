<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'حسابي') — متجر الملابس</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700;800&family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        (function () {
            const saved = localStorage.getItem('darkMode');
            if (saved === 'true' || (saved === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @stack('styles')
</head>

<body class="bg-stone-50 dark:bg-zinc-950 antialiased min-h-screen flex flex-col">

    @include('partials.header')
    @include('partials.customer-tabs')

    <main class="flex-1">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="fixed top-24 left-1/2 -translate-x-1/2 z-50 
                        bg-forest-700 dark:bg-gold-500 dark:text-ink text-white 
                        px-6 py-3 text-sm font-medium rounded shadow-lg 
                        flex items-center gap-3 max-w-md">
                <i class="fa-solid fa-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="fixed top-24 left-1/2 -translate-x-1/2 z-50 
                        bg-red-600 text-white 
                        px-6 py-3 text-sm font-medium rounded shadow-lg 
                        flex items-center gap-3 max-w-md">
                <i class="fa-solid fa-xmark"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')

    <div id="toast-container" class="fixed bottom-6 left-6 z-[100] space-y-2"></div>

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
            const styles = {
                success: 'bg-forest-700 dark:bg-gold-500 dark:text-ink text-white',
                error: 'bg-red-600 text-white',
                info: 'bg-stone-900 text-white',
            };
            const icons = {
                success: '<i class="fa-solid fa-check"></i>',
                error: '<i class="fa-solid fa-xmark"></i>',
                info: '<i class="fa-solid fa-circle-info"></i>',
            };

            const toast = document.createElement('div');
            toast.className = `${styles[type]} px-5 py-3 rounded shadow-lg text-sm font-medium flex items-center gap-3 transition-all duration-300 opacity-0 translate-y-2`;
            toast.innerHTML = `${icons[type]}<span>${message}</span>`;
            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-y-2');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        async function toggleWishlist(productId) {
            @auth
                try {
                    const res = await fetch('/customer/wishlist/toggle', {
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