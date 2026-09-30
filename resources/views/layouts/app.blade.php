<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'حسابي') — متجر الملابس</title>

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

    @stack('styles')
</head>

<body class="bg-stone-50 dark:bg-zinc-950 antialiased min-h-screen flex flex-col">

    {{-- Header --}}
    @include('partials.header')

    {{-- Customer Tabs --}}
    @include('partials.customer-tabs')

    {{-- Main Content --}}
    <main class="flex-1">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition
                 class="fixed top-24 left-1/2 -translate-x-1/2 z-50 
                        bg-forest-700 dark:bg-gold-500 dark:text-ink text-white 
                        px-6 py-3 text-sm font-medium rounded shadow-lg flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition
                 class="fixed top-24 left-1/2 -translate-x-1/2 z-50 
                        bg-red-600 text-white 
                        px-6 py-3 text-sm font-medium rounded shadow-lg flex items-center gap-3">
                <i class="fas fa-times-circle text-lg"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    @stack('scripts')
</body>

</html>