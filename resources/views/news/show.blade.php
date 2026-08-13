@php
    use Filament\Forms\Components\RichEditor\RichContentRenderer;
    use Statikbe\FilamentFlexibleContentBlockPages\Models\Page;

    $imageUrl = $news->image ? asset('storage/' . $news->image) : null;
    $descriptionHtml = RichContentRenderer::make($news->description)->toUnsafeHtml();
    $newsListUrl = Page::getUrl('berita') ?? url('/');
@endphp

<x-layouts.app>
    <x-slot name="header">
        <x-header />
    </x-slot>

    {{-- Back link --}}
    <div class="border-b border-zinc-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-4">
            <a href="{{ $newsListUrl }}" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 transition-colors hover:text-emerald-700">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Berita
            </a>
        </div>
    </div>

    {{-- Article --}}
    <article class="bg-white">
        <div class="mx-auto max-w-4xl px-4 py-12 sm:py-16">
            <header>
                <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-wider text-zinc-400">
                    <time datetime="{{ $news->created_at?->toIso8601String() }}">
                        <i class="fa-regular fa-calendar mr-1.5 text-emerald-700"></i>
                        {{ $news->created_at?->format('d M Y') }}
                    </time>
                    <span class="text-zinc-300">•</span>
                    <span>
                        <i class="fa-solid fa-circle-user mr-1.5 text-emerald-700"></i>
                        {{ $news->author?->name ?? '—' }}
                    </span>
                </div>

                <h1 class="mt-4 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl lg:text-5xl">
                    {{ $news->title }}
                </h1>
            </header>

            @if ($imageUrl)
                <figure class="mt-8 overflow-hidden rounded-2xl">
                    <img src="{{ $imageUrl }}" alt="{{ $news->title }}" class="h-full w-full object-cover">
                </figure>
            @endif

            <div class="prose prose-lg prose-zinc mt-10 max-w-none [&_a]:font-medium [&_a]:text-emerald-700 [&_a]:underline [&_strong]:text-zinc-900 [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:tracking-tight [&_h2]:text-zinc-900 [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:tracking-tight [&_h3]:text-zinc-900 [&_img]:rounded-xl [&_img]:shadow-sm">
                {!! $descriptionHtml !!}
            </div>

            <div class="mt-10 border-t border-zinc-200 pt-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                            <i class="fa-solid fa-circle-user"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-zinc-900">{{ $news->author?->name ?? 'Admin' }}</p>
                            <p class="text-xs text-zinc-500">Penulis</p>
                        </div>
                    </div>

                    <a href="{{ $newsListUrl }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 px-5 py-2.5 text-sm font-semibold text-zinc-700 transition-colors hover:border-emerald-600 hover:text-emerald-700">
                        <i class="fa-solid fa-newspaper"></i>
                        Semua Berita
                    </a>
                </div>
            </div>
        </div>
    </article>

    @if ($recentNews->isNotEmpty())
        <section class="bg-zinc-50">
            <div class="mx-auto max-w-6xl px-4 py-12">
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900 sm:text-3xl">Berita Lainnya</h2>
                <p class="mt-2 text-zinc-600">Jangan lewatkan kabar terbaru dari kami.</p>

                <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($recentNews as $newsItem)
                        <x-news-card :news="$newsItem" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
