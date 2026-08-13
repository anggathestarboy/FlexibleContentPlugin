@props([
    'title' => null,
    'intro' => null,
    'items' => [],
    'backgroundColour' => null,
    'class' => '',
])

@php
    use Illuminate\Support\Str;

    $accordionId = 'collapsible-' . Str::slug((string) ($title ?: 'group')) . '-' . Str::random(6);
@endphp

<section @class([
    'content-block content-block--collapsible-group',
    $backgroundColour,
    $class,
])>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:py-12">
        @if ($title)
            <h2 class="text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">
                {{ $title }}
            </h2>
        @endif

        @if ($intro)
            <div class="mt-4 text-base leading-relaxed text-zinc-600 [&_a]:font-medium [&_a]:text-emerald-700 [&_a]:underline">
                {!! $intro !!}
            </div>
        @endif

        <div class="{{ ($title || $intro) ? 'mt-8' : '' }} space-y-3">
            @foreach ($items as $index => $item)
                <details @class([
                    'group overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm transition-shadow hover:shadow-md',
                ]) @if ($item['isOpenByDefault'] ?? false) open @endif>
                    <summary
                        class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4 select-none [&::-webkit-details-marker]:hidden">
                        <span class="text-base font-semibold text-zinc-900 transition-colors group-open:text-emerald-700">
                            {{ $item['title'] }}
                        </span>
                        <i
                            class="fa-solid fa-chevron-down shrink-0 text-sm text-zinc-400 transition-transform duration-300 group-open:rotate-180"></i>
                    </summary>
                    <div class="border-t border-zinc-100 px-5 py-4 text-sm leading-relaxed text-zinc-600">
                        {!! $item['content'] !!}
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>
