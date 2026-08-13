<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Actions\FlexibleLocaleSwitcher;

class EditNews extends EditRecord
{
    use Translatable;

    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            FlexibleLocaleSwitcher::make(),
            DeleteAction::make(),
        ];
    }
}
