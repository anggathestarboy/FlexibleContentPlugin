<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Statikbe\FilamentFlexibleContentBlockPages\Models\Tag;
use Statikbe\FilamentFlexibleContentBlockPages\Resources\PageResource as BasePageResource;
use Statikbe\FilamentFlexibleContentBlockPages\Resources\PageResource\Schemas\PageFormSchema;

class PageResource extends BasePageResource
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make(flexiblePagesTrans('pages.tabs.lbl'))
                    ->columnSpan(2)
                    ->tabs([
                        Tab::make(flexiblePagesTrans('pages.tabs.general'))
                            ->icon(Heroicon::GlobeAlt)
                            ->schema(PageFormSchema::getGeneralTabFields()),
                        Tab::make(flexiblePagesTrans('pages.tabs.content'))
                            ->icon(Heroicon::OutlinedRectangleGroup)
                            ->schema(PageFormSchema::getContentTabFields()),
                        Tab::make(flexiblePagesTrans('pages.tabs.overview'))
                            ->icon(Heroicon::OutlinedMagnifyingGlass)
                            ->schema(PageFormSchema::getOverviewTabFields()),
                        Tab::make(flexiblePagesTrans('pages.tabs.seo'))
                            ->icon(Heroicon::OutlinedGlobeAlt)
                            ->schema(PageFormSchema::getSEOTabFields()),
                        Tab::make(flexiblePagesTrans('pages.tabs.advanced'))
                            ->icon(Heroicon::OutlinedWrenchScrewdriver)
                            ->schema([
                                ...PageFormSchema::getAdvancedTabFields(),
                                static::getTagsField(),
                            ]),
                    ])
                    ->persistTabInQueryString(),
            ]);
    }

    private static function getTagsField(): Select
    {
        return Select::make('tags')
            ->label(flexiblePagesTrans('tags.tag_plural_lbl'))
            ->relationship('tags', 'name')
            ->multiple()
            ->preload()
            ->getOptionLabelFromRecordUsing(
                fn (Tag $record): string => (string) $record->getTranslation('name', app()->getLocale())
            );
    }
}
