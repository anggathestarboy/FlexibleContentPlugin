@props([
    'links' => [],
])

@php
    $currentLocale = app()->getLocale();
@endphp

<div class="relative inline-block" data-language-switch>
    <button type="button"
            data-language-switch-toggle
            aria-haspopup="true"
            aria-expanded="false"
            class="inline-flex h-10 items-center gap-2 rounded-full border border-zinc-200 bg-zinc-50 px-4 py-2 text-sm font-medium text-zinc-700 transition-colors hover:bg-zinc-200">
        <span>{{ strtoupper($currentLocale) }}</span>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
        </svg>
    </button>

    <div data-language-switch-menu
         class="absolute right-0 z-50 mt-2 hidden min-w-36 origin-top-right rounded-xl border border-zinc-200 bg-white p-1 shadow-lg">
        @foreach ($links as $locale => $url)
            <a href="{{ $url }}"
               class="flex items-center justify-between gap-3 rounded-lg px-3 py-2 text-sm transition-colors {{ $locale === $currentLocale ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-zinc-700 hover:bg-zinc-100' }}">
                <span>{{ strtoupper($locale) }}</span>
                @if ($locale === $currentLocale)
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                @endif
            </a>
        @endforeach
    </div>
</div>
