<section @class([
    'content-block content-block--text-image',
    $getBackgroundColourClass(),
])>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:py-12">
        <div @class([
            'grid grid-cols-1 items-center gap-8 md:gap-12',
            'md:grid-cols-2' => $hasImage() && $imagePosition !== 'center',
            'justify-items-center' => $imagePosition === 'center',
        ])>
            <div @class([
                'max-w-2xl',
                'md:order-2' => $hasImage() && $imagePosition === 'left',
                $imagePosition === 'center' ? 'flex flex-col items-center text-center' : '',
            ])>
                @if ($title)
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">
                        {{ $replaceParameters($title) }}
                    </h2>
                @endif

                @if ($text)
                    <div class="mt-4 text-base leading-relaxed text-zinc-600 [&_a]:font-medium [&_a]:text-emerald-700 [&_a]:underline">
                        {!! $replaceParameters($text) !!}
                    </div>
                @endif

                @if ($callToActions)
                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        @foreach ($callToActions as $callToAction)
                            <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($hasImage())
                <figure @class([
                    'md:order-1' => $hasImage() && $imagePosition === 'left',
                    'max-w-2xl' => $imagePosition === 'center',
                ])>
                    {{ $getImageMedia(attributes: ['class' => 'h-auto w-full', 'loading' => 'lazy']) }}

                    @if ($imageCopyright)
                        <figcaption class="mt-2 text-center text-xs text-zinc-400">
                            &copy; {{ $replaceParameters($imageCopyright) }}
                        </figcaption>
                    @endif
                </figure>
            @endif
        </div>
    </div>
</section>
