<?php

namespace App\Form\Forms;

use Filament\Forms\Components\Select;
use Statikbe\FilamentFlexibleContentBlockPages\Form\Forms\MenuItemForm as BaseMenuItemForm;

class MenuItemForm extends BaseMenuItemForm
{
    protected static function getLinkTypeField(): Select
    {
        return parent::getLinkTypeField()->required(false);
    }
}
