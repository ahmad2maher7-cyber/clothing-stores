<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — الصفحة غير موجودة</title>
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4"
      style="background-color: var(--bg-secondary);">

    <div class="text-center max-w-md">
        <div class="text-[150px] leading-none mb-4">🔍</div>
        <h1 class="text-6xl font-bold mb-4" style="color: var(--gold);">404</h1>
        <h2 class="text-2xl font-bold mb-3" style="color: var(--text-primary);">الصفحة غير موجودة</h2>
        <p class="text-[13px] mb-8" style="color: var(--text-secondary);">
            عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها
        </p>
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="/"
               class="inline-flex items-center gap-2 h-11 px-6 rounded-lg font-bold text-white text-[13px] transition hover:opacity-90"
               style="background-color: var(--gold);">
                🏠 الرئيسية
            </a>
            <a href="/products"
               class="inline-flex items-center gap-2 h-11 px-6 rounded-lg font-semibold text-[13px] transition"
               style="background-color: var(--bg-tertiary); color: var(--text-primary);">
                🛍️ تسوق الآن
            </a>
        </div>
    </div>
</body>
</html>