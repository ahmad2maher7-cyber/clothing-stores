@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge([
    'class' => 'w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest-600 dark:focus:border-gold-400 focus:ring-0 focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 disabled:opacity-50 disabled:cursor-not-allowed rounded transition-colors'
]) }}>