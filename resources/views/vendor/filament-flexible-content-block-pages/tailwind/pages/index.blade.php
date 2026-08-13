@php
    use \Statikbe\FilamentFlexibleContentBlockPages\Facades\FilamentFlexibleContentBlockPages;
    use \Statikbe\FilamentFlexibleContentBlockPages\FlexibleContentBlockPagesPanel;
    use \Statikbe\FilamentFlexibleContentBlockPages\Models\Page;

    /* @var Page $page */

    $pageResource = FilamentFlexibleContentBlockPages::config()->getPageResource();
@endphp

<x-layouts.app>
    <x-flexible-pages-edit-page-button
        :page="$page"
        :edit-url="$pageResource::getUrl('edit', ['record' => $page], true, FlexibleContentBlockPagesPanel::ID)"
    />

    <main class="prose-headings:font-base">

        <x-flexible-hero :page="$page"/>

        <x-flexible-content-blocks :page="$page"/>

    </main>
</x-layouts.app>
