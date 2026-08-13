@php
    use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CollapsibleItemData;

    $accordionItems = $collapsibleItems
        ->map(fn (CollapsibleItemData $item) => [
            'title' => $item->title,
            'content' => $item->text,
            'isOpenByDefault' => $item->isOpenByDefault,
        ])
        ->all();
@endphp

<x-collapsible-text-group
    :title="$groupTitle ? $replaceParameters($groupTitle) : null"
    :intro="$groupIntro ? $replaceParameters($groupIntro) : null"
    :items="$accordionItems"
    :backgroundColour="$getBackgroundColourClass()" />
