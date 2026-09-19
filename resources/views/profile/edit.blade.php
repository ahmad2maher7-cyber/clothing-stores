<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👤 الملف الشخصي
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">الاسم الكامل</p>
                        <p class="font-medium text-lg">{{ auth()->user()->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">البريد الإلكتروني</p>
                        <p class="font-medium">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">رقم الهاتف</p>
                        <p class="font-medium">{{ auth()->user()->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">نوع الحساب</p>
                        <p class="font-medium">
                            @switch(auth()->user()->role)
                                @case('admin') 🛡️ مشرف @break
                                @case('merchant') 🏪 تاجر @break
                                @default 👤 زبون
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>