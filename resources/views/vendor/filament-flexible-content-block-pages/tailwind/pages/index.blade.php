@php
    use \Statikbe\FilamentFlexibleContentBlockPages\Facades\FilamentFlexibleContentBlockPages;
    use \Statikbe\FilamentFlexibleContentBlockPages\FlexibleContentBlockPagesPanel;
    use \Statikbe\FilamentFlexibleContentBlockPages\Models\Settings;
    use \Statikbe\FilamentFlexibleContentBlockPages\Models\Page;

    /* @var Page $page */

    $pageResource = FilamentFlexibleContentBlockPages::config()->getPageResource();
@endphp

<x-flexible-pages-base-layout>
    <x-flexible-pages-edit-page-button
        :page="$page"
        :edit-url="$pageResource::getUrl('edit', ['record' => $page], true, FlexibleContentBlockPagesPanel::ID)"
    />

    <header>
        <x-flexible-pages-language-switch/>
    </header>

    <main class="prose-headings:font-base">

        <x-flexible-hero :page="$page"/>

        <x-flexible-content-blocks :page="$page"/>

    </main>

    <footer>
        <div>{{flexiblePagesSetting(Settings::SETTING_FOOTER_COPYRIGHT)}}</div>
    </footer>
</x-flexible-pages-base-layout>
