@php
    use Statikbe\FilamentFlexibleContentBlockPages\Facades\FilamentFlexibleContentBlockPages;
    use Statikbe\FilamentFlexibleContentBlockPages\FlexibleContentBlockPagesPanel;
    use Statikbe\FilamentFlexibleContentBlockPages\Models\Page;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionField;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\HeroCallToActionSection;
    use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks;

    /* @var Page $page */

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

    <x-flexible-content-blocks :page="$page" />
</x-layouts.page>
