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
        ->map(fn (array $callToAction) => CallToActionData::create($callToAction, $buttonStyleClasses))
        ->toArray();

    $heroImageMedia = $hasHeroImage
        ? $page->getHeroImageMedia(null, [
            'class' => 'h-full w-full object-cover object-center',
            'loading' => 'eager',
        ])
        : null;

    $news = \App\Models\News::query()->latest()->paginate(9);
@endphp

<x-layouts.page>
    <x-flexible-pages-edit-page-button
        :page="$page"
        :edit-url="$pageResource::getUrl('edit', ['record' => $page], true, FlexibleContentBlockPagesPanel::ID)"
    />

    <x-slot name="hero">
        <x-hero
            :title="$title ? FilamentFlexibleContentBlocks::replaceParameters($title) : null"
            :intro="$introText ? FilamentFlexibleContentBlocks::replaceParameters($intro) : null"
            :eyebrow="$heroImageTitle ? FilamentFlexibleContentBlocks::replaceParameters($heroImageTitle) : null"
            :image="$heroImageMedia"
            :imageCopyright="$heroImageCopyright ? FilamentFlexibleContentBlocks::replaceParameters($heroImageCopyright) : null"
            :callToActions="$heroCallToActions"
        />
    </x-slot>

    {{-- Berita --}}
    <section class="bg-zinc-50">
        <div class="mx-auto max-w-6xl px-4 py-12">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">Berita</h2>
                    <p class="mt-2 text-zinc-600">Berita terbaru dari kami.</p>
                </div>
            </div>

            @if ($news->isNotEmpty())
                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($news as $newsItem)
                        <x-news-card :news="$newsItem" />
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $news->links() }}
                </div>
            @else
                <p class="mt-8 text-zinc-500">Belum ada berita.</p>
            @endif
        </div>
    </section>
</x-layouts.page>
