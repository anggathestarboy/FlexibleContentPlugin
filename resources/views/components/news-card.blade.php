@props(['news'])

@php
    use Filament\Forms\Components\RichEditor\RichContentRenderer;
    use Illuminate\Support\Str;

    $imageUrl = $news->image ? asset('storage/' . $news->image) : null;
    $excerpt = filled($news->description)
        ? Str::limit(strip_tags((string) RichContentRenderer::make($news->description)->toText()), 140)
        : '';
@endphp

<article class="group flex flex-col overflow-hidden cursor-pointer rounded-xl border border-zinc-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
    @if ($imageUrl)
        <div class="aspect-video overflow-hidden">
            <img src="{{ $imageUrl }}" alt="{{ $news->title }}" loading="lazy"
                 class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
        </div>
    @else
        <div class="flex aspect-video items-center justify-center bg-zinc-100 text-zinc-400">
            <i class="fa-solid fa-newspaper text-4xl"></i>
        </div>
    @endif

    <div class="flex flex-col gap-2 p-5">
        <time class="text-xs font-semibold uppercase tracking-wider text-zinc-400">
            {{ $news->created_at?->format('d M Y') }}
        </time>

        <h3 class="text-lg font-bold leading-snug text-zinc-900 transition-colors group-hover:text-emerald-700">
            {{ $news->title }}
        </h3>

        @if ($excerpt)
            <p class="text-sm leading-relaxed text-zinc-600">{{ $excerpt }}</p>
        @endif

        <div class="mt-auto flex items-center gap-2 pt-3 text-xs font-medium text-zinc-500">
            <i class="fa-solid fa-circle-user text-emerald-700"></i>
            {{ $news->author?->name ?? '—' }}
        </div>
    </div>
</article>
