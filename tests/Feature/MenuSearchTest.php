<?php

namespace Tests\Feature;

use App\Models\Page;
use Tests\TestCase;

class MenuSearchTest extends TestCase
{
    public function test_search_pramuka_with_id_locale(): void
    {
        app()->setLocale('id');

        $result = Page::searchForMenuItems('pramuka')->limit(50)->get();

        $this->assertNotEmpty($result, 'Search "pramuka" with locale id should return the Pramuka page.');
        $this->assertEquals(8, $result->first()->getKey());
    }

    public function test_search_pramuka_with_en_locale(): void
    {
        app()->setLocale('en');

        $result = Page::searchForMenuItems('pramuka')->limit(50)->get();

        $this->assertNotEmpty($result, 'Search "pramuka" with locale en should still find the Pramuka page via fallback.');
        $this->assertEquals(8, $result->first()->getKey());
    }
}
