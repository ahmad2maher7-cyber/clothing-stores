<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center justify-center gap-2 h-12 px-6 text-xs font-semibold tracking-widest uppercase text-ink dark:text-cream border border-stone-300 dark:border-stone-700 hover:border-forest-500 dark:hover:border-gold-400 hover:text-forest-700 dark:hover:text-gold-400 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-forest-500 dark:focus:ring-gold-400 focus:ring-offset-2 focus:ring-offset-canvas dark:focus:ring-offset-zinc-950 disabled:opacity-50 disabled:cursor-not-allowed rounded transition-all',
]) }}>
    {{ $slot }}
</button>