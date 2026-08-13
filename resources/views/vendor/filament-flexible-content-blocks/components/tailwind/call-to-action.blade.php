@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData $callToAction */

    $isSecondary = str_contains($callToAction->buttonStyle ?? '', 'secondary');
    $buttonStyleClass = $isSecondary
        ? 'border border-emerald-600 text-emerald-700 hover:bg-emerald-50'
        : 'bg-emerald-600 text-white hover:bg-emerald-700';
@endphp

@if ($callToAction->url)
    <div {{ $attributes }}>
        <a href="{{ $callToAction->url }}"
           @if ($callToAction->label) title="{{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}" @endif
           class="inline-flex items-center justify-center rounded-full px-6 py-3 text-sm font-semibold transition-colors {{ $buttonStyleClass }} @if ($isFullyClickable) before:absolute before:inset-0 @endif"
           @if ($callToAction->openNewWindow) target="_blank" rel="noopener noreferrer" @endif>
            @if ($callToAction->label)
                {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}
            @else
                &xrarr;
            @endif
        </a>
    </div>
@endif
