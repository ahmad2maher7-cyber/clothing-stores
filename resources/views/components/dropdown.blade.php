@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1.5'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-56',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute z-50 mt-2 {{ $width }} rounded shadow-lg {{ $alignmentClasses }}"
         style="display: none;"
         @click="open = false">

        <div class="bg-white dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 rounded {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>