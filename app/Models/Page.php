<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Tags\HasTags;
use Statikbe\FilamentFlexibleContentBlockPages\Models\Page as BasePage;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Statikbe\FilamentFlexibleContentBlockPages\Models\Tag> $tags
 */
class Page extends BasePage
{
    use HasTags;

    /**
     * Search pages for menu items using the title of the active locale
     * (e.g. the Indonesian version when Indonesian is active), instead of
     * matching against the raw translatable JSON column.
     */
    public function scopeSearchForMenuItems($query, string $search)
    {
        $search = trim($search);

        return $query->when($search !== '', function ($query) use ($search): void {
            $locale = app()->getLocale();
            $grammar = $query->getQuery()->getGrammar();

            $escaped = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], mb_strtolower($search));
            $term = "%{$escaped}%";

            $column = $grammar->wrap("title->{$locale}");

            $query->where(function ($query) use ($column, $term): void {
                $query->orWhereRaw("LOWER({$column}) LIKE ? ESCAPE '!'", [$term]);
            });
        });
    }
}
