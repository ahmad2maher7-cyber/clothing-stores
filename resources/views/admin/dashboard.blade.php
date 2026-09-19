<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🛡️ لوحة تحكم المشرف
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">المستخدمون</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $stats['users'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">المتاجر</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['stores'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">المنتجات</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['products'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">الطلبات</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['orders'] }}</p>
                </div>
            </div>

            <div class="mt-8 bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">إحصائيات سريعة</h3>
                <ul class="space-y-2 text-gray-700">
                    <li>👥 التجار: <strong>{{ $stats['merchants'] }}</strong></li>
                    <li>🛒 الزبائن: <strong>{{ $stats['customers'] }}</strong></li>
                    <li>💰 الإيرادات: <strong>{{ number_format($stats['revenue'], 2) }} ₪</strong></li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>