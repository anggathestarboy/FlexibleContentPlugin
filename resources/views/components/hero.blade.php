@props([
    'title' => null,
    'intro' => null,
    'eyebrow' => null,
    'image' => null,
    'imageAlt' => null,
    'imageCopyright' => null,
    'callToActions' => [],
    'backgroundColour' => null,
    'class' => '',
])

@php
    $hasImage = filled($image);
@endphp

<section @class([
    'content-block content-block--hero relative overflow-hidden',
    $backgroundColour,
    $hasImage ? 'bg-zinc-900' : 'bg-zinc-100',
    $class,
])>
    @if ($hasImage)
        <div class="absolute inset-0">
            {!! $image !!}
            {{-- Gradient dari kiri ke kanan agar teks di kiri kontras & gambar tetap terlihat --}}
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/45 to-transparent"></div>
        </div>
    @endif

    <div class="relative z-10 mx-auto max-w-6xl px-4 py-20 sm:py-28">
        <div class="grid grid-cols-1 items-center gap-8 md:grid-cols-2 md:gap-12">
            {{-- Kiri: title --}}
            <div>
                @if ($eyebrow)
                    <p
                        class="text-xs font-bold uppercase tracking-[0.25em] {{ $hasImage ? 'text-emerald-400' : 'text-emerald-600' }}">
                        {{ $eyebrow }}
                    </p>
                @endif

                @if ($title)
                    <h1
                        class="mt-4 text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl {{ $hasImage ? 'text-white drop-shadow-sm' : 'text-zinc-900' }}">
                        {{ $title }}
                    </h1>
                @endif
            </div>

            {{-- Kanan: desc + CTA --}}
            <div @class([
                'flex flex-col items-start',
                $hasImage ? 'text-gray-50 [&_a]:text-white [&_a]:underline' : 'text-zinc-700 [&_a]:text-zinc-900 [&_a]:underline',
            ])>
                @if ($intro)
                    <div class="">
                        {!! $intro !!}
                    </div>
                @endif

                @if (count($callToActions))
                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        @foreach ($callToActions as $callToAction)
                            <x-flexible-call-to-action :data="$callToAction" />
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($hasImage && $imageCopyright)
        <small
            class="absolute bottom-3 right-4 z-10 rounded-md bg-black/50 px-2.5 py-1 text-xs text-white/80 backdrop-blur-sm">
            &copy; {{ $imageCopyright }}
        </small>
    @endif
</section>
