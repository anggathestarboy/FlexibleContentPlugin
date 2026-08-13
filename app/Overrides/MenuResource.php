<?php

namespace App\Overrides;

use App\Overrides\MenuResource\Pages\ManageMenuItems;
use Statikbe\FilamentFlexibleContentBlockPages\Resources\MenuResource as BaseMenuResource;
use Statikbe\FilamentFlexibleContentBlockPages\Resources\MenuResource\Pages\CreateMenu;
use Statikbe\FilamentFlexibleContentBlockPages\Resources\MenuResource\Pages\EditMenu;
use Statikbe\FilamentFlexibleContentBlockPages\Resources\MenuResource\Pages\ListMenus;

class MenuResource extends BaseMenuResource
{
    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record:id}/edit'),
            'items' => ManageMenuItems::route('/{record:id}/items'),
        ];
    }
}
