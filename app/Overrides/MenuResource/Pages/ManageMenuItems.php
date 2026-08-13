<?php

namespace App\Overrides\MenuResource\Pages;

use App\Form\Forms\MenuItemForm;
use App\Overrides\MenuResource;
use Statikbe\FilamentFlexibleContentBlockPages\Resources\MenuResource\Pages\ManageMenuItems as BaseManageMenuItems;

class ManageMenuItems extends BaseManageMenuItems
{
    protected static string $resource = MenuResource::class;

    protected function getFormSchema(): array
    {
        return MenuItemForm::getSchema();
    }
}
