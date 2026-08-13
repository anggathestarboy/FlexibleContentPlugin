@php
    $links = collect(LaravelLocalization::getSupportedLocales())
        ->mapWithKeys(fn ($properties, $localeCode) => [
            $localeCode => $page && is_object($page) ? $page->getViewUrl($localeCode) : '#',
        ])
        ->all();
@endphp

<x-language-switch :links="$links"/>
