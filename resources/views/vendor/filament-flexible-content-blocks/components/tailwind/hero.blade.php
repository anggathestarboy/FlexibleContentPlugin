<section @class([
    'relative overflow-hidden bg-zinc-100',
    'py-12 sm:py-16' => ! $hasHeroImage(),
])>
    @if ($hasHeroImage())
        <div class="absolute inset-0">
            {{ $getHeroImageMedia(null, [
                'class' => 'w-full h-full object-cover object-center',
                'loading' => 'eager',
            ]) }}
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-zinc-900/70"></div>
    @endif

    <div class="relative z-10 mx-auto max-w-6xl px-4 py-16 sm:py-24">
        <div class="max-w-3xl">
            @if ($title)
                <h1 @class([
                    'text-4xl font-bold tracking-tight sm:text-5xl',
                    $hasHeroImage() ? 'text-white' : 'text-zinc-900',
                ])>
                    {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title) }}
                </h1>
            @endif

            @if ($intro)
                <div @class([
                    'mt-4 text-lg leading-relaxed sm:text-xl [&_a]:underline hover:[&_a]:no-underline',
                    $hasHeroImage() ? 'text-white/90' : 'text-zinc-700',
                ])>
                    {!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($intro) !!}
                </div>
            @endif

            @if ($heroCallToActions)
                <div class="mt-6 flex flex-wrap gap-3">
                    @foreach ($heroCallToActions as $callToAction)
                        <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if ($hasHeroImage() && $heroImageCopyright)
        <small class="absolute bottom-2 right-2 z-10 rounded bg-black/40 px-2 py-1 text-xs text-white">
            &copy; {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($heroImageCopyright) }}
        </small>
    @endif

    @if ($hasHeroVideoUrl())
        <x-flexible-background-video :videoUrl="$getHeroVideoUrl()"
                                     wrapperClass="min-h-[337px] md:min-h-[474px]"
                                     :overlayImageMedia="$hasHeroImage()
                                         ? $getHeroImageMedia(null, [
                                             'class' => 'w-full h-full object-cover object-center',
                                             'loading' => 'lazy',
                                         ])
                                         : null"
                                     :overlayOpacity="0.5"/>
    @endif
</section>
