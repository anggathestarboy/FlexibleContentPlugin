@php
    use Statikbe\FilamentFlexibleContentBlockPages\Facades\FilamentFlexibleContentBlockPages;
    use Statikbe\FilamentFlexibleContentBlockPages\FlexibleContentBlockPagesPanel;
    use Statikbe\FilamentFlexibleContentBlockPages\Models\Settings;
    use Statikbe\FilamentFlexibleContentBlockPages\Models\Menu;
    use Statikbe\FilamentFlexibleContentBlockPages\Components\Data\MenuData;
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

    $mainMenu = Menu::code('main')->first();
    $menuItems = $mainMenu ? MenuData::create($mainMenu, app()->getLocale())->items : collect();

    $buttonStyleClasses = CallToActionField::getButtonStyleClasses(HeroCallToActionSection::class);
    $heroCallToActions = collect($page->hero_call_to_actions ?? [])
        ->map(fn (array $callToAction) => CallToActionData::create($callToAction, $buttonStyleClasses))
        ->toArray();
@endphp

<x-flexible-pages-base-layout>
    <x-flexible-pages-edit-page-button
        :page="$page"
        :edit-url="$pageResource::getUrl('edit', ['record' => $page], true, FlexibleContentBlockPagesPanel::ID)"
    />

    {{-- Header --}}
    <header class="sticky top-0 z-50 border-b border-zinc-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex max-w-5xl items-center justify-between gap-6 px-4 py-3">
            <a href="{{ url('/') }}" class="text-xl font-bold tracking-tight text-zinc-900">
                {{ flexiblePagesSetting(Settings::SETTING_SITE_TITLE) }}
            </a>
            <div class="flex flex-1 items-center justify-center">
                @if ($menuItems->isNotEmpty())
                    <ul class="flex items-center gap-1">
                        @foreach ($menuItems as $menuItem)
                            <li class="group relative">
                                <a href="{{ $menuItem->url }}"
                                   @if ($menuItem->target !== '_self') target="{{ $menuItem->target }}" rel="noopener noreferrer" @endif
                                   class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors hover:bg-zinc-100 hover:text-zinc-900 {{ $menuItem->isCurrentMenuItem() ? 'text-zinc-900' : 'text-zinc-700' }}">
                                    {{ $menuItem->label }}
                                    @if ($menuItem->hasChildren())
                                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                        </svg>
                                    @endif
                                </a>
                                @if ($menuItem->hasChildren())
                                    <ul class="invisible absolute left-0 top-full z-50 mt-2 min-w-52 rounded-xl border border-zinc-200 bg-white p-2 opacity-0 shadow-lg transition-opacity group-hover:visible group-hover:opacity-100">
                                        @foreach ($menuItem->children as $child)
                                            <li>
                                                <a href="{{ $child->url }}"
                                                   @if ($child->target !== '_self') target="{{ $child->target }}" rel="noopener noreferrer" @endif
                                                   class="block rounded-lg px-3 py-2 text-sm text-zinc-700 transition-colors hover:bg-zinc-100 hover:text-zinc-900">
                                                    {{ $child->label }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <x-flexible-pages-language-switch/>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-zinc-100">
        @if ($hasHeroImage)
            <div class="absolute inset-0">
                {!! $page->getHeroImageMedia(null, [
                    'class' => 'w-full h-full object-cover object-center',
                    'loading' => 'eager',
                ]) !!}
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-zinc-900/70"></div>
        @endif

        <div class="relative z-10 mx-auto max-w-4xl px-4 py-24 text-center sm:py-32">
            @if ($heroImageTitle)
                <p class="text-sm font-semibold uppercase tracking-[0.2em] {{ $hasHeroImage ? 'text-white/70' : 'text-zinc-500' }}">
                    {{ FilamentFlexibleContentBlocks::replaceParameters($heroImageTitle) }}
                </p>
            @endif

            @if ($title)
                <h1 class="mt-3 text-4xl font-bold tracking-tight sm:text-6xl {{ $hasHeroImage ? 'text-white' : 'text-zinc-900' }}">
                    {{ FilamentFlexibleContentBlocks::replaceParameters($title) }}
                </h1>
            @endif

            @if ($introText)
                <div class="mx-auto mt-6 max-w-2xl text-lg leading-relaxed sm:text-xl {{ $hasHeroImage ? 'text-white/90 [&_a]:text-white [&_a]:underline' : 'text-zinc-700 [&_a]:text-zinc-900 [&_a]:underline' }}">
                    {!! FilamentFlexibleContentBlocks::replaceParameters($intro) !!}
                </div>
            @endif

            @if (count($heroCallToActions))
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    @foreach ($heroCallToActions as $callToAction)
                        <a href="{{ $callToAction->url }}"
                           @if ($callToAction->openNewWindow) target="_blank" rel="noopener noreferrer" @endif
                           title="{{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}"
                           class="inline-flex items-center rounded-full px-7 py-3 text-sm font-semibold transition-colors
                                  {{ str_contains($callToAction->buttonStyle, 'ghost')
                                      ? 'ring-1 ring-white/40 ' . ($hasHeroImage ? 'text-white hover:bg-white/20' : 'ring-zinc-300 text-zinc-900 hover:bg-zinc-100')
                                      : ($hasHeroImage ? 'bg-white text-zinc-900 hover:bg-zinc-100' : 'bg-zinc-900 text-white hover:bg-zinc-700') }}">
                            {{ FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($heroImageCopyright)
            <small class="absolute bottom-2 right-2 z-10 rounded bg-black/40 px-2 py-1 text-xs text-white">
                {{ FilamentFlexibleContentBlocks::replaceParameters($heroImageCopyright) }}
            </small>
        @endif
    </section>

    {{-- Content blocks --}}
    <main class="mx-auto max-w-5xl px-4 py-16">
        <x-flexible-content-blocks :page="$page"/>
    </main>

    {{-- Footer --}}
    <footer class="border-t border-zinc-200 bg-zinc-50">
        <div class="mx-auto flex max-w-5xl flex-col items-center gap-2 px-4 py-8 text-center text-sm text-zinc-500">
            <div>{{ flexiblePagesSetting(Settings::SETTING_FOOTER_COPYRIGHT) }}</div>
            <div>&copy; {{ date('Y') }}</div>
        </div>
    </footer>
</x-flexible-pages-base-layout>
