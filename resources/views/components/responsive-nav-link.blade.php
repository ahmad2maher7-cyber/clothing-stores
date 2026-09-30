@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-4 pe-4 py-3 text-start text-sm font-semibold text-forest-700 dark:text-gold-400 bg-forest-50 dark:bg-forest-950/40 border-r-4 border-forest-700 dark:border-gold-400 focus:outline-none transition-colors'
    : 'block w-full ps-4 pe-4 py-3 text-start text-sm font-medium text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-800 hover:text-forest-700 dark:hover:text-gold-400 border-r-4 border-transparent focus:outline-none transition-colors';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>