@php
    $tabs = [
        'index'      => ['route' => 'merchant.settings.index',      'label' => 'المعلومات الأساسية', 'icon' => 'fa-store'],
        'appearance' => ['route' => 'merchant.settings.appearance', 'label' => 'الهوية البصرية',   'icon' => 'fa-palette'],
        'branches'   => ['route' => 'merchant.settings.branches',   'label' => 'الفروع',            'icon' => 'fa-code-branch'],
        'shipping'   => ['route' => 'merchant.settings.shipping',   'label' => 'مناطق الشحن',        'icon' => 'fa-truck-fast'],
        'policies'   => ['route' => 'merchant.settings.policies',   'label' => 'السياسات',           'icon' => 'fa-file-contract'],
    ];
@endphp

<div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded-lg mb-6 overflow-hidden">
    <div class="flex overflow-x-auto">
        @foreach($tabs as $key => $tab)
            <a href="{{ route($tab['route']) }}"
               class="flex items-center gap-2 px-5 py-4 whitespace-nowrap border-b-2 transition-colors
                      {{ $activeTab === $key 
                         ? 'border-forest-700 dark:border-gold-400 text-forest-700 dark:text-gold-400 font-medium bg-forest-50 dark:bg-forest-950/30' 
                         : 'border-transparent text-ink-muted dark:text-cream/60 hover:text-forest-700 dark:hover:text-gold-400 hover:bg-stone-50 dark:hover:bg-zinc-800' }}">
                <i class="fa-solid {{ $tab['icon'] }} text-sm"></i>
                <span class="text-sm">{{ $tab['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>