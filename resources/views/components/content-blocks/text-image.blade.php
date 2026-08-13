@props([
    'title' => null,
    'text' => null,
    'image' => null,
    'imageAlt' => null,
    'imagePosition' => 'right',
    'imageCopyright' => null,
    'backgroundColour' => null,
    'callToActions' => [],
    'class' => '',
])

@php
    $hasImage = filled($image);
    $isCentered = $imagePosition === 'center';
    $imageOnLeft = $imagePosition === 'left';
@endphp

<section @class([
    'content-block content-block--text-image',
    $backgroundColour,
    $class,
])>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:py-12">
        <div @class([
            'grid grid-cols-1 items-center gap-8 md:gap-12',
            'md:grid-cols-2' => $hasImage && ! $isCentered,
            'justify-items-center' => $isCentered,
        ])>
            <div @class([
                'max-w-2xl',
                'md:order-2' => $imageOnLeft,
                $isCentered ? 'flex flex-col items-center text-center' : '',
            ])>
                @if ($title)
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">
                        {{ $title }}
                    </h2>
                @endif

                @if ($text)
                    <div class="mt-4 text-base leading-relaxed text-zinc-600 [&_a]:font-medium [&_a]:text-emerald-700 [&_a]:underline">
                        {!! $text !!}
                    </div>
                @endif

                @if ($callToActions && count($callToActions))
                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        @foreach ($callToActions as $callToAction)
                            <x-flexible-call-to-action :data="$callToAction" />
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($hasImage)
                <figure @class([
                    'md:order-1' => $imageOnLeft,
                    'max-w-2xl' => $isCentered,
                ])>
                    <img src="{{ $image }}"
                         alt="{{ $imageAlt ?: ($title ?: '') }}"
                         loading="lazy"
                         class="h-auto w-full">
                    @if ($imageCopyright)
                        <figcaption class="mt-2 text-center text-xs text-zinc-400">
                            &copy; {{ $imageCopyright }}
                        </figcaption>
                    @endif
                </figure>
            @endif
        </div>
    </div>
</section>
