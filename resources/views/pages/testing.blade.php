@php
    use Statikbe\FilamentFlexibleContentBlockPages\Facades\FilamentFlexibleContentBlockPages;
    use Statikbe\FilamentFlexibleContentBlockPages\FlexibleContentBlockPagesPanel;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionField;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\HeroCallToActionSection;
    use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks;

    /* @var \Statikbe\FilamentFlexibleContentBlockPages\Models\Page $page */

    $pageResource = FilamentFlexibleContentBlockPages::config()->getPageResource();

    $title = $page->getTitle();
    $intro = $page->getIntro();
    $introText = trim(strip_tags((string) $intro));
    $hasHeroImage = $page->hasHeroImage();
    $heroImageTitle = $page->getHeroImageTitle();
    $heroImageCopyright = $page->getHeroImageCopyright();

    $buttonStyleClasses = CallToActionField::getButtonStyleClasses(HeroCallToActionSection::class);
    $heroCallToActions = collect($page->hero_call_to_actions ?? [])
        ->map(fn(array $callToAction) => CallToActionData::create($callToAction, $buttonStyleClasses))
        ->toArray();

    $pageTags = collect($page->tags ?? [])
        ->filter(fn($tag) => (bool) $tag->tagType?->has_seo_pages)
        ->map(
            fn($tag) => [
                'name' => $tag->name,
                'url' => FilamentFlexibleContentBlockPages::config()
                    ->getRouteHelper()
                    ->getTagPageUrl($tag, app()->getLocale()),
                'colour' => $tag->tagType?->colour,
            ],
        )
        ->values();

    $latestNews = \App\Models\News::query()->latest()->take(3)->get();

    use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CollapsibleGroupBlock;

    $contentBlockClasses = collect($page::registerContentBlocks())
        ->mapWithKeys(fn ($class) => [$class::getName() => $class]);

    $collapsibleGroupInstances = [];
    $contentBlockData = [];

    foreach (collect($page->content_blocks ?? []) as $blockData) {
        if (($blockData['type'] ?? null) === CollapsibleGroupBlock::getName()) {
            $collapsibleGroupInstances[] = new CollapsibleGroupBlock($page, $blockData['data']);
        } else {
            $contentBlockData[] = $blockData;
        }
    }
@endphp

<x-layouts.app>
    <x-flexible-pages-edit-page-button :page="$page" :edit-url="$pageResource::getUrl('edit', ['record' => $page], true, FlexibleContentBlockPagesPanel::ID)" />

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-zinc-900">
        @if ($hasHeroImage)
            <div class="absolute inset-0">
                {!! $page->getHeroImageMedia(null, [
                    'class' => 'h-full w-full object-cover object-center',
                    'loading' => 'eager',
                ]) !!}
            </div>
            {{-- Gradient dari kiri ke kanan agar teks di kiri sangat kontras & gambar di kanan tetap terlihat jelas --}}
            <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/55 to-transparent"></div>
        @endif

        {{-- Kontainer sejajar logo (max-w-6xl) dan teks rata kiri --}}
        <div class="relative z-10 mx-auto max-w-6xl px-4 py-20 text-left sm:py-28">
            <div class="max-w-3xl">
                @if ($heroImageTitle)
                    <p
                        class="text-xs font-bold uppercase tracking-[0.25em] {{ $hasHeroImage ? 'text-emerald-400' : 'text-emerald-600' }}">
                        {{ FilamentFlexibleContentBlocks::replaceParameters($heroImageTitle) }}
                    </p>
                @endif

                @if ($title)
                    <h1
                        class="mt-4 text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl drop-shadow-sm {{ $hasHeroImage ? 'text-white' : 'text-zinc-900' }}">
                        {{ FilamentFlexibleContentBlocks::replaceParameters($title) }}
                    </h1>
                @endif

                @if ($introText)
                    <div
                        class="mt-6 text-lg leading-relaxed sm:text-xl {{ $hasHeroImage ? 'text-zinc-200 [&_a]:text-white [&_a]:underline' : 'text-zinc-700 [&_a]:text-zinc-900 [&_a]:underline' }}">
                        {!! FilamentFlexibleContentBlocks::replaceParameters($intro) !!}
                    </div>
                @endif

                @if (count($heroCallToActions))
                    <div class="mt-10 flex flex-wrap items-center justify-start gap-4">
                        @foreach ($heroCallToActions as $callToAction)
                            <a href="{{ $callToAction->url }}"
                                @if ($callToAction->openNewWindow) target="_blank" rel="noopener noreferrer" @endif
                                title="{{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}"
                                class="inline-flex items-center justify-center rounded-xl px-8 py-3.5 text-sm font-semibold transition-all duration-200 hover:-translate-y-0.5 shadow-md hover:shadow-lg
                                  {{ str_contains($callToAction->buttonStyle, 'ghost')
                                      ? ($hasHeroImage
                                          ? 'border border-white/30 text-white hover:bg-white/10 backdrop-blur-sm'
                                          : 'border border-zinc-300 text-zinc-900 hover:bg-zinc-100')
                                      : ($hasHeroImage
                                          ? 'bg-emerald-600 text-white hover:bg-emerald-500'
                                          : 'bg-emerald-600 text-white hover:bg-emerald-700') }}">
                                {{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }} &rarr;
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if ($heroImageCopyright)
            <small
                class="absolute bottom-3 right-4 z-10 rounded-md bg-black/50 px-2.5 py-1 text-xs text-white/80 backdrop-blur-sm">
                {{ FilamentFlexibleContentBlocks::replaceParameters($heroImageCopyright) }}
            </small>
        @endif
    </section>

    

    @if ($pageTags->isNotEmpty())
        {{-- Tags --}}
        <section class="border-b border-zinc-200 bg-white">
            <div class="mx-auto max-w-6xl px-4 py-6">
                <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-zinc-400">Tags</p>
                <ul class="flex flex-wrap gap-2">
                    @foreach ($pageTags as $pageTag)
                        <li>
                            <a href="{{ $pageTag['url'] }}"
                                class="inline-flex items-center rounded-full border px-3.5 py-1.5 text-sm font-medium transition-opacity hover:opacity-80"
                                style="border-color: {{ $pageTag['colour'] ?? '#d4d4d8' }}; color: {{ $pageTag['colour'] ?? '#3f3f46' }};">
                                {{ $pageTag['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Content blocks --}}
    <main class="mx-auto max-w-6xl px-4 py-12">
        <div id="content-blocks-wrapper">
            @foreach ($contentBlockData as $blockData)
                @if (($blockClass = $contentBlockClasses->get($blockData['type'] ?? null)) !== null)
                    @php
                        $block = new $blockClass($page, $blockData['data']);
                    @endphp
                    {{ $block->withAttributes([])->render()->with($block->data()) }}
                @endif
            @endforeach
        </div>
    </main>

    @if ($latestNews->isNotEmpty())
        {{-- News --}}
        <section class="border-t border-zinc-200 bg-zinc-50">
            <div class="mx-auto max-w-6xl px-4 py-12">
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">News</h2>
                <p class="mt-2 text-zinc-600">Berita terbaru dari kami.</p>

                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($latestNews as $newsItem)
                        <x-news-card :news="$newsItem" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($collapsibleGroupInstances)
        {{-- Collapsible text group (accordion) --}}
        @foreach ($collapsibleGroupInstances as $block)
            {{ $block->withAttributes([])->render()->with($block->data()) }}
        @endforeach
    @endif
</x-layouts.app>
