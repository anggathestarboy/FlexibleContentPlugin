<?php

namespace App\Models;

use Spatie\Tags\HasTags;
use Statikbe\FilamentFlexibleContentBlockPages\Models\Page as BasePage;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Statikbe\FilamentFlexibleContentBlockPages\Models\Tag> $tags
 */
class Page extends BasePage
{
    use HasTags;
}
