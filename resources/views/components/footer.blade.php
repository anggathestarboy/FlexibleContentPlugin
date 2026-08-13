@php
    use Statikbe\FilamentFlexibleContentBlockPages\Models\Settings;
@endphp

<footer class="border-t border-zinc-200 bg-zinc-50">
    <div class="mx-auto flex max-w-6xl flex-col items-center gap-2 px-4 py-8 text-center text-sm text-zinc-500">
        <div>{{ flexiblePagesSetting(Settings::SETTING_FOOTER_COPYRIGHT) }}</div>
        <div>&copy; {{ date('Y') }}</div>
    </div>
</footer>
