<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - الصفحة غير موجودة</title>
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <div class="text-[150px] leading-none mb-4">🔍</div>
        <h1 class="text-6xl font-bold text-indigo-600 mb-4">404</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">الصفحة غير موجودة</h2>
        <p class="text-gray-500 mb-8">
            عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها
        </p>
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ url('/') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition">
                🏠 الرئيسية
            </a>
            <a href="{{ url('/products') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium transition">
                🛍️ تسوق الآن
            </a>
        </div>
    </div>
</body>
</html>