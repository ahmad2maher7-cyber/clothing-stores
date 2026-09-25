<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التاجر') — {{ $store->name ?? 'متجري' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Cairo', sans-serif; }
        [x-cloak] { display: none !important; }
        .line-clamp-1, .line-clamp-2 {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-1 { -webkit-line-clamp: 1; }
        .line-clamp-2 { -webkit-line-clamp: 2; }

        /* Desktop Layout */
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
                background-color: var(--bg-primary);
                border-left: 1px solid var(--border-light);
            }
            .merchant-main {
                flex: 1;
                min-width: 0;
                display: flex;
                flex-direction: column;
            }
        }

        /* Mobile */
        @media (max-width: 1023.98px) {
            .merchant-sidebar-desktop {
                display: none;
            }
            .merchant-main {
                display: flex;
                flex-direction: column;
            }
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
</head>
<body class="antialiased" style="background-color: var(--bg-secondary);">

    <div x-data="{ sidebarOpen: false }" class="merchant-layout">

        {{-- ============ Sidebar Desktop (Always Visible) ============ --}}
        <aside class="merchant-sidebar-desktop">
            @include('merchant.partials.sidebar-content')
        </aside>

        {{-- ============ Sidebar Mobile (Overlay) ============ --}}
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
            <div @click="sidebarOpen = false"
                 class="absolute inset-0"
                 style="background-color: rgba(0,0,0,0.5);"></div>

            <aside class="absolute inset-y-0 right-0 w-64 flex flex-col shadow-2xl overflow-y-auto"
                   style="background-color: var(--bg-primary);">
                @include('merchant.partials.sidebar-content')
            </aside>
        </div>

        {{-- ============ Main Content ============ --}}
        <div class="merchant-main">
            @include('merchant.partials.topbar')

            <main class="flex-1 p-4 md:p-6" style="background-color: var(--bg-secondary);">

                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                         class="mb-5 rounded-lg p-4 text-[13px] flex items-center gap-2"
                         style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                         class="mb-5 rounded-lg p-4 text-[13px] flex items-center gap-2"
                         style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>