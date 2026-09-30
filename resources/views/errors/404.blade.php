<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - الصفحة غير موجودة</title>
    <link href="https://fonts.bunny.net/css?family=tajawal:400,500,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>body { font-family: 'Tajawal', sans-serif; }</style>
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <div class="text-[140px] leading-none mb-4 text-gray-200">404</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">الصفحة غير موجودة</h1>
        <p class="text-sm text-gray-500 mb-8 leading-relaxed">
            عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها
        </p>
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ url('/') }}" class="btn-primary">
                🏠 الرئيسية
            </a>
            <a href="{{ url('/products') }}" class="btn-secondary">
                🛍️ تسوق الآن
            </a>
        </div>
    </div>
</body>
</html>