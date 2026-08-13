@php
    use Statikbe\FilamentFlexibleContentBlockPages\Components\Data\MenuData;
    use Statikbe\FilamentFlexibleContentBlockPages\Models\Menu;
    use Statikbe\FilamentFlexibleContentBlockPages\Models\Settings;
@endphp

@props([
    'contactUrl' => '#',
    'contactLabel' => __('Hubungi Kami'),
])

@php
    $mainMenu = Menu::code('main')->first();
    $menuItems = $mainMenu ? MenuData::create($mainMenu, app()->getLocale())->items : collect();
    $siteTitle = flexiblePagesSetting(Settings::SETTING_SITE_TITLE);
@endphp

<header class="sticky top-0 z-50 border-b border-zinc-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
        <div class="flex items-center gap-2 sm:gap-3">
            <a href="{{ url('/') }}" class="shrink-0" aria-label="{{ $siteTitle }}">
                <img src="{{ asset('storage/logo_islamiyah.png') }}"
                     alt="{{ $siteTitle }}"
                     class="h-10 w-auto sm:h-12">
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden lg:block" aria-label="Menu utama">
                <ul class="flex items-center gap-1">
                @foreach ($menuItems as $menuItem)
                    <li class="group relative">
                        <a href="{{ $menuItem->url }}"
                           @if ($menuItem->target !== '_self') target="{{ $menuItem->target }}" rel="noopener noreferrer" @endif
                           class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors hover:bg-zinc-100 hover:text-zinc-900 {{ $menuItem->isCurrentMenuItem() ? 'text-zinc-700' : 'text-green-700' }}">
                            {{ $menuItem->label }}
                            @if ($menuItem->hasChildren())
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                                </svg>
                            @endif
                        </a>
                        @if ($menuItem->hasChildren())
                            <ul class="invisible absolute left-0 top-full z-50 min-w-52 rounded-xl border border-zinc-200 bg-white p-2 opacity-0 shadow-lg transition-opacity group-hover:visible group-hover:opacity-100">
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
        </nav>
        </div>

        {{-- Right actions (desktop) --}}
        <div class="hidden shrink-0 items-center gap-3 lg:flex">
            @isset($languageSwitch)
                {{ $languageSwitch }}
            @else
                <x-flexible-pages-language-switch/>
            @endisset

            <a href="{{ $contactUrl }}"
               class="inline-flex items-center rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-emerald-700">
                {{ $contactLabel }}
            </a>
        </div>

        {{-- Mobile hamburger --}}
        <button type="button"
                id="mobile-menu-toggle"
                aria-expanded="false"
                aria-controls="mobile-menu"
                class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-zinc-700 transition-colors hover:bg-zinc-100 lg:hidden">
            <span class="sr-only">{{ __('Menu') }}</span>
            <svg class="icon-open h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
            <svg class="icon-close hidden h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Mobile panel --}}
    <div id="mobile-menu" class="hidden border-t border-zinc-200 bg-white lg:hidden">
        <div class="mx-auto max-w-6xl space-y-4 px-4 py-4">
            @if ($menuItems->isNotEmpty())
                <nav class="flex flex-col" aria-label="Menu utama">
                    @foreach ($menuItems as $menuItem)
                        <div class="border-b border-zinc-100 py-1 last:border-b-0">
                            <a href="{{ $menuItem->url }}"
                               @if ($menuItem->target !== '_self') target="{{ $menuItem->target }}" rel="noopener noreferrer" @endif
                               class="block rounded-lg px-3 py-2 text-base font-medium text-zinc-800 transition-colors hover:bg-zinc-100 {{ $menuItem->isCurrentMenuItem() ? 'text-emerald-700' : '' }}">
                                {{ $menuItem->label }}
                            </a>
                            @if ($menuItem->hasChildren())
                                <ul class="ml-4 border-l border-zinc-200 pl-3">
                                    @foreach ($menuItem->children as $child)
                                        <li>
                                            <a href="{{ $child->url }}"
                                               @if ($child->target !== '_self') target="{{ $child->target }}" rel="noopener noreferrer" @endif
                                               class="block rounded-lg px-3 py-2 text-sm text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900">
                                                {{ $child->label }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </nav>
            @endif

            <div class="flex flex-col items-stretch gap-3 border-t border-zinc-200 pt-4">
                @isset($languageSwitch)
                    <div class="flex justify-center">{{ $languageSwitch }}</div>
                @else
                    <div class="flex justify-center">
                        <x-flexible-pages-language-switch/>
                    </div>
                @endisset

                <a href="{{ $contactUrl }}"
                   class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-emerald-700">
                    {{ $contactLabel }}
                </a>
            </div>
        </div>
    </div>
</header>
