@php
    use Statikbe\FilamentFlexibleContentBlockPages\Facades\FilamentFlexibleContentBlockPages;
    use Statikbe\FilamentFlexibleContentBlockPages\Models\Settings;
    use Statikbe\FilamentFlexibleContentBlockPages\Routes\Contracts\HandlesPageRoutes;

    /** @var \Statikbe\FilamentFlexibleContentBlockPages\Models\Tag $tag */

    $seoDescription = trim(strip_tags((string) $tag->seo_description));

    $tagLocaleLinks = collect(LaravelLocalization::getSupportedLocales())
        ->mapWithKeys(fn ($properties, $localeCode) => [
            $localeCode => LaravelLocalization::getLocalizedUrl($localeCode, route(
                HandlesPageRoutes::ROUTE_SEO_TAG_PAGE,
                ['tag' => $tag->getTranslation('slug', $localeCode)]
            )),
        ])
        ->all();
@endphp

<x-layouts.app>
    <x-slot name="languageSwitch">
        <x-language-switch :links="$tagLocaleLinks"/>
    </x-slot>

    {{-- Tag hero --}}
    <section class="bg-zinc-100">
        <div class="mx-auto max-w-5xl px-4 py-16 sm:py-20">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-zinc-500">Tags</p>
            <h1 class="mt-3 text-4xl font-bold tracking-tight text-zinc-900 sm:text-5xl">{{ $tag->name }}</h1>

            @if ($seoDescription)
                <div class="mt-4 max-w-2xl text-lg leading-relaxed text-zinc-700">{{ $seoDescription }}</div>
            @endif

            @if ($contentCounts && count($contentCounts) > 0)
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($contentCounts as $type => $count)
                        <span class="inline-flex items-center rounded-full bg-white px-3 py-1 text-sm font-medium text-zinc-700 ring-1 ring-inset ring-zinc-200">
                            {{ $count }} {{ $type }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Tagged content --}}
    <main class="mx-auto max-w-5xl px-4 py-16">
        @if ($taggedContent->count() > 0)
            <div class="space-y-10">
                @foreach ($taggedContent as $item)
                    <article class="border-b border-zinc-200 pb-10 last:border-b-0">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-xs font-semibold uppercase tracking-widest text-zinc-400">
                                {{ $modelLabels[$item::class] ?? class_basename($item) }}
                            </span>
                            <time class="text-sm text-zinc-400">
                                {{ ($item->publishing_begins_at ?? $item->created_at)->format('d M Y') }}
                            </time>
                        </div>

                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-zinc-900">
                            <a href="{{ $item->getViewUrl() }}" class="transition-colors hover:text-zinc-600">
                                {{ $item->getTitle() }}
                            </a>
                        </h2>

                        @if (method_exists($item, 'getIntro') && $item->getIntro())
                            <p class="mt-3 leading-relaxed text-zinc-600">
                                {{ Str::limit(strip_tags($item->getIntro()), 400) }}
                            </p>
                        @endif

                        @if (method_exists($item, 'tags') && $item->tags->count() > 0)
                            <ul class="mt-4 flex flex-wrap gap-2">
                                @foreach ($item->tags->take(5) as $itemTag)
                                    <li class="inline-flex items-center rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-600">
                                        {{ $itemTag->name }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </article>
                @endforeach
            </div>

            @if ($taggedContent->hasPages())
                <nav class="mt-10">
                    {{ $taggedContent->links() }}
                </nav>
            @endif
        @else
            <div class="py-16 text-center">
                <p class="text-lg text-zinc-500">{{ flexiblePagesTrans('tag_pages.no_content', ['tag' => $tag->name]) }}</p>
            </div>
        @endif
    </main>
</x-layouts.app>
