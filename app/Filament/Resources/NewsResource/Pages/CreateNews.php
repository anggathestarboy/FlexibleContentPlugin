<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Actions\FlexibleLocaleSwitcher;

class CreateNews extends CreateRecord
{
    use Translatable;

    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            FlexibleLocaleSwitcher::make(),
        ];
    }
}
