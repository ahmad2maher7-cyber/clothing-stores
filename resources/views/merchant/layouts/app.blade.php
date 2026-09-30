<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التاجر') — {{ $store->name ?? 'متجري' }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    {{-- Fonts --}}
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

    <style>
        [x-cloak] { display: none !important; }

        @media (min-width: 1024px) {
            .merchant-layout {
                display: flex;
                min-height: 100vh;
            }
            .merchant-sidebar-desktop {
                width: 260px;
                flex-shrink: 0;
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
            }
            .merchant-main {
                flex: 1;
                min-width: 0;
                display: flex;
                flex-direction: column;
            }
        }

        @media (max-width: 1023.98px) {
            .merchant-sidebar-desktop { display: none; }
            .merchant-main {
                display: flex;
                flex-direction: column;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-stone-50 dark:bg-zinc-950 antialiased">

    <div x-data="{ sidebarOpen: false }" class="merchant-layout">

        <aside class="merchant-sidebar-desktop bg-white dark:bg-zinc-900 border-l border-stone-200 dark:border-stone-800">
            @include('merchant.partials.sidebar')
        </aside>

        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
            <div @click="sidebarOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <aside x-show="sidebarOpen"
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in duration-200"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="translate-x-full"
                   class="absolute inset-y-0 right-0 w-72 flex flex-col shadow-2xl overflow-y-auto bg-white dark:bg-zinc-900">
                @include('merchant.partials.sidebar')
            </aside>
        </div>

        <div class="merchant-main">
            @include('merchant.partials.topbar')

            <main class="flex-1 p-4 md:p-6 lg:p-8 bg-stone-50 dark:bg-zinc-950">

                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                         x-transition
                         class="mb-6 flex items-center gap-3 px-5 py-4 bg-forest-50 dark:bg-forest-950/30 border border-forest-200 dark:border-forest-900/50 text-forest-800 dark:text-forest-300 rounded-lg">
                        <i class="fa-solid fa-circle-check text-lg shrink-0"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                         x-transition
                         class="mb-6 flex items-center gap-3 px-5 py-4 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-300 rounded-lg">
                        <i class="fa-solid fa-circle-exclamation text-lg shrink-0"></i>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

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
    </script>

    @stack('scripts')
</body>

</html>