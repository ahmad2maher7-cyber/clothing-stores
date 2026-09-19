<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👤 مرحباً {{ auth()->user()->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">طلباتي</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $stats['orders'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">قيد المعالجة</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['pending'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">تم التسليم</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['delivered'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <p class="text-gray-500 text-sm">المفضلة</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['wishlist'] }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>