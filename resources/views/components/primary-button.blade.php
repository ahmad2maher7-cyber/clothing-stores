<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center gap-2 h-12 px-6 text-xs font-semibold tracking-widest uppercase bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 hover:bg-forest-800 dark:hover:bg-gold-400 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-forest-500 dark:focus:ring-gold-400 focus:ring-offset-2 focus:ring-offset-canvas dark:focus:ring-offset-zinc-950 disabled:opacity-50 disabled:cursor-not-allowed rounded transition-all',
]) }}>
    {{ $slot }}
</button><button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center gap-2 h-12 px-6 text-xs font-semibold tracking-widest uppercase bg-forest-700 dark:bg-gold-500 text-white dark:text-forest-950 hover:bg-forest-800 dark:hover:bg-gold-400 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-forest-500 dark:focus:ring-gold-400 focus:ring-offset-2 focus:ring-offset-canvas dark:focus:ring-offset-zinc-950 disabled:opacity-50 disabled:cursor-not-allowed rounded transition-all',
]) }}>
    {{ $slot }}
</button>