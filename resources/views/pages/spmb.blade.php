@php
    use Statikbe\FilamentFlexibleContentBlockPages\Facades\FilamentFlexibleContentBlockPages;
    use Statikbe\FilamentFlexibleContentBlockPages\FlexibleContentBlockPagesPanel;
    use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CallToActionBlock;
    use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CardsBlock;
    use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\CallToActionField;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData;
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Groups\HeroCallToActionSection;
    use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks;

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

    $contentBlockClasses = collect($page::registerContentBlocks())
        ->mapWithKeys(fn ($class) => [$class::getName() => $class]);

    $blockInstances = collect($page->content_blocks ?? [])
        ->map(fn ($blockData) => [
            'type' => $blockData['type'] ?? null,
            'instance' => isset($contentBlockClasses[$blockData['type'] ?? null])
                ? new $contentBlockClasses[$blockData['type']]($page, $blockData['data'])
                : null,
        ])
        ->all();

    // Kelompokkan block: TextImageBlock yang langsung diikuti oleh CallToActionBlock
    // dipasangkan jadi satu section (teks+gambar kiri, card CTA kanan). Block lain
    // (termasuk CallToActionBlock yang berdiri sendiri) tetap dirender satu per satu.
    $renderGroups = [];
    $totalBlocks = count($blockInstances);

    for ($i = 0; $i < $totalBlocks; $i++) {
        $currentInstance = $blockInstances[$i]['instance'];
        $nextInstance = $blockInstances[$i + 1]['instance'] ?? null;

        if ($currentInstance instanceof TextImageBlock && $nextInstance instanceof CallToActionBlock) {
            $renderGroups[] = [
                'kind' => 'text-image-with-cta',
                'text' => $currentInstance,
                'cta' => $nextInstance,
            ];
            $i++; // block CallToActionBlock sudah dipakai, lewati di iterasi berikutnya

            continue;
        }

        $renderGroups[] = [
            'kind' => 'single',
            'instance' => $currentInstance,
        ];
    }
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

    @foreach ($renderGroups as $group)
        @if ($group['kind'] === 'text-image-with-cta')
            @php
                $textBlock = $group['text'];
                $ctaBlock = $group['cta'];
            @endphp

            <section @class([
                'content-block content-block--text-image',
                $textBlock->getBackgroundColourClass(),
            ])>
                <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-3">
                        {{-- Konten teks + gambar: kiri di layar besar --}}
                        <div @class([
                            'order-2 flex flex-col space-y-6 lg:order-1 lg:col-span-2',
                            'items-center text-center' => $textBlock->imagePosition === 'center',
                        ])>
                            @if ($textBlock->title)
                                <h2 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                                    {{ FilamentFlexibleContentBlocks::replaceParameters($textBlock->title) }}
                                </h2>
                            @endif

                            @if ($textBlock->text)
                                <div class="text-sm leading-relaxed text-slate-500 sm:text-base [&_a]:font-medium [&_a]:text-orange-500 [&_a]:underline">
                                    {!! FilamentFlexibleContentBlocks::replaceParameters($textBlock->text) !!}
                                </div>
                            @endif

                            @if ($textBlock->hasImage())
                                <figure class="mt-4 w-full">
                                    {!! $textBlock->getImageMedia(attributes: ['class' => 'h-auto w-full object-cover rounded-xl shadow-sm', 'loading' => 'lazy']) !!}

                                    @if ($textBlock->imageCopyright)
                                        <figcaption class="mt-2 text-center text-xs text-slate-400">
                                            &copy; {{ FilamentFlexibleContentBlocks::replaceParameters($textBlock->imageCopyright) }}
                                        </figcaption>
                                    @endif
                                </figure>
                            @endif
                        </div>

                        {{-- Card Call to Action (block terpisah): selalu kanan di layar besar --}}
                        @if ($ctaBlock->callToActions)
                           <div class="order-1 lg:order-2 lg:col-span-1 lg:sticky lg:top-24">
                                <div class="rounded-2xl bg-gray-100 p-6 sm:p-8 ">
                                    @if ($ctaBlock->hasImage())
                                        <figure class="mb-4">
                                            {!! $ctaBlock->getImageMedia(attributes: ['class' => 'h-auto w-full rounded-xl object-cover', 'loading' => 'lazy']) !!}
                                        </figure>
                                    @endif

                                    @if ($ctaBlock->title)
                                        <h3 class="text-xl font-bold text-slate-900">
                                            {{ FilamentFlexibleContentBlocks::replaceParameters($ctaBlock->title) }}
                                        </h3>
                                    @endif

                                    @if ($ctaBlock->text)
                                        <div class="mt-3 text-xs leading-relaxed text-slate-600 sm:text-sm [&_a]:font-medium [&_a]:text-orange-500 [&_a]:underline">
                                            {!! FilamentFlexibleContentBlocks::replaceParameters($ctaBlock->text) !!}
                                        </div>
                                    @endif

                                    @foreach ($ctaBlock->callToActions as $callToAction)
                                        @if ($callToAction->url)
                                            <div class="mt-6">
                                                <a href="{{ $callToAction->url }}"
                                                    @if ($callToAction->label) title="{{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}" @endif
                                                    @if ($callToAction->openNewWindow) target="_blank" rel="noopener noreferrer" @endif
                                                    class="inline-block rounded-md bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow transition-all hover:bg-green-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500">
                                                    {{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }} &rarr;
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @else
            @php
                $blockInstance = $group['instance'];
            @endphp

            @if ($blockInstance instanceof TextImageBlock)
                <section @class([
                    'content-block content-block--text-image',
                    $blockInstance->getBackgroundColourClass(),
                ])>
                    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-3">
                            <div @class([
                                'flex flex-col space-y-6',
                                'lg:col-span-2' => $blockInstance->hasImage() || $blockInstance->callToActions,
                                'lg:col-span-3' => !$blockInstance->hasImage() && !$blockInstance->callToActions,
                                'items-center text-center' => $blockInstance->imagePosition === 'center',
                            ])>
                                @if ($blockInstance->title)
                                    <h2 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                                        {{ FilamentFlexibleContentBlocks::replaceParameters($blockInstance->title) }}
                                    </h2>
                                @endif

                                @if ($blockInstance->text)
                                    <div class="text-sm leading-relaxed text-slate-500 sm:text-base [&_a]:font-medium [&_a]:text-orange-500 [&_a]:underline">
                                        {!! FilamentFlexibleContentBlocks::replaceParameters($blockInstance->text) !!}
                                    </div>
                                @endif

                                @if ($blockInstance->hasImage())
                                    <figure class="mt-4 w-full">
                                        {!! $blockInstance->getImageMedia(attributes: ['class' => 'h-auto w-full object-cover rounded-xl shadow-sm', 'loading' => 'lazy']) !!}

                                        @if ($blockInstance->imageCopyright)
                                            <figcaption class="mt-2 text-center text-xs text-slate-400">
                                                &copy; {{ FilamentFlexibleContentBlocks::replaceParameters($blockInstance->imageCopyright) }}
                                            </figcaption>
                                        @endif
                                    </figure>
                                @endif
                            </div>

                            @if ($blockInstance->callToActions)
                                <div class="lg:col-span-1 lg:sticky lg:top-8">
                                    <div class="rounded-2xl bg-[#FFF6EC] p-6 sm:p-8 shadow-sm">
                                        @foreach ($blockInstance->callToActions as $callToAction)
                                            @if ($callToAction->title || $callToAction->label)
                                                <h3 class="text-xl font-bold text-slate-900">
                                                    {{ $callToAction->title ?? FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}
                                                </h3>
                                            @endif

                                            @if ($callToAction->description ?? false)
                                                <p class="mt-3 text-xs leading-relaxed text-slate-600 sm:text-sm">
                                                    {{ $callToAction->description }}
                                                </p>
                                            @endif

                                            @if ($callToAction->url)
                                                <div class="mt-6">
                                                    <a href="{{ $callToAction->url }}"
                                                        @if ($callToAction->label) title="{{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}" @endif
                                                        @if ($callToAction->openNewWindow) target="_blank" rel="noopener noreferrer" @endif
                                                        class="inline-block rounded-md bg-[#F59E0B] px-5 py-2.5 text-sm font-semibold text-white shadow transition-all hover:bg-[#D97706] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500">
                                                        {{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            @elseif ($blockInstance instanceof CallToActionBlock)
                {{-- CallToActionBlock berdiri sendiri (tidak didahului TextImageBlock) --}}
                <section @class([
                    'content-block content-block--call-to-action',
                    $blockInstance->getBackgroundColourClass(),
                ])>
                    <div class="mx-auto max-w-3xl px-4 py-12 text-center sm:px-6 lg:px-8">
                        @if ($blockInstance->hasImage())
                            <figure class="mx-auto mb-6 max-w-md">
                                {!! $blockInstance->getImageMedia(attributes: ['class' => 'h-auto w-full rounded-xl object-cover', 'loading' => 'lazy']) !!}
                            </figure>
                        @endif

                        @if ($blockInstance->title)
                            <h2 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                                {{ FilamentFlexibleContentBlocks::replaceParameters($blockInstance->title) }}
                            </h2>
                        @endif

                        @if ($blockInstance->text)
                            <div class="mt-3 text-sm leading-relaxed text-slate-500 sm:text-base [&_a]:font-medium [&_a]:text-orange-500 [&_a]:underline">
                                {!! FilamentFlexibleContentBlocks::replaceParameters($blockInstance->text) !!}
                            </div>
                        @endif

                        @if ($blockInstance->callToActions)
                            <div class="mt-6 flex flex-wrap justify-center gap-3">
                                @foreach ($blockInstance->callToActions as $callToAction)
                                    @if ($callToAction->url)
                                        <a href="{{ $callToAction->url }}"
                                            @if ($callToAction->label) title="{{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}" @endif
                                            @if ($callToAction->openNewWindow) target="_blank" rel="noopener noreferrer" @endif
                                            class="inline-block rounded-md bg-[#F59E0B] px-5 py-2.5 text-sm font-semibold text-white shadow transition-all hover:bg-[#D97706] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500">
                                            {{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>
            @elseif ($blockInstance instanceof CardsBlock)
                <section @class([
                    'content-block content-block--cards',
                    $blockInstance->getBackgroundColourClass(),
                ])>
                    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                        <div class="flex flex-col gap-6">
                            @if ($blockInstance->title)
                                <h2 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                                    {{ FilamentFlexibleContentBlocks::replaceParameters($blockInstance->title) }}
                                </h2>
                            @endif

                            @php
                                $nrOfItems = count($blockInstance->cards);
                                $actualNrGridCols = min($blockInstance->gridColumns, $nrOfItems);

                                $isCol2 = $actualNrGridCols >= 2;
                                $isCol3 = $actualNrGridCols >= 3;
                                $isCol4 = $actualNrGridCols === 4;
                            @endphp

                            <ul @class([
                                'grid gap-6',
                                'sm:grid-cols-2' => $isCol2,
                                'lg:grid-cols-3' => $isCol3,
                                'xl:grid-cols-4' => $isCol4,
                            ])>
                                @foreach ($blockInstance->cards as $card)
                                    <li class="flex flex-col justify-between rounded-xl bg-[#FFF6EC] p-6 sm:p-8">
                                        <div>
                                            @if ($card->hasImage())
                                                <div class="mb-4 overflow-hidden rounded-lg">
                                                    {!! $blockInstance->getCardImageMedia($card->cardId, $card->title, false, ['class' => 'w-full object-cover']) !!}
                                                </div>
                                            @endif

                                            @if ($card->title)
                                                <h3 class="text-lg font-bold text-slate-800">
                                                    {{ FilamentFlexibleContentBlocks::replaceParameters($card->title) }}
                                                </h3>
                                            @endif

                                            @if ($card->text)
                                                <div class="mt-3 text-xs leading-relaxed text-slate-600 sm:text-sm [&_a]:font-medium [&_a]:text-orange-500 [&_a]:underline">
                                                    {!! FilamentFlexibleContentBlocks::replaceParameters($card->text) !!}
                                                </div>
                                            @endif
                                        </div>

                                        @if ($card->callToActions)
                                            <div class="mt-6">
                                                @foreach ($card->callToActions as $callToAction)
                                                    @if ($callToAction->url)
                                                        <a href="{{ $callToAction->url }}"
                                                            @if ($callToAction->label) title="{{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}" @endif
                                                            @if ($callToAction->openNewWindow) target="_blank" rel="noopener noreferrer" @endif
                                                            class="inline-block rounded-md bg-[#F59E0B] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-[#D97706]">
                                                            {{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </section>
            @elseif ($blockInstance)
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    {{ $blockInstance->withAttributes([])->render()->with($blockInstance->data()) }}
                </div>
            @endif
        @endif
    @endforeach
</x-layouts.page>