<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Statikbe\FilamentFlexibleContentBlockPages\Models\Page;

Route::get('/', function () {
    return view('welcome');
});

// News detail route must be registered before the page routes to avoid conflicts with the {parent}/{page} catch-all.
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'localize'],
], function () {
    Route::get('/berita/{news}', [NewsController::class, 'show'])
        ->name('news.show')
        ->defaults('page', Page::getByCode('berita'));
});

\Statikbe\FilamentFlexibleContentBlockPages\Facades\FilamentFlexibleContentBlockPages::routes();
