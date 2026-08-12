<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

\Statikbe\FilamentFlexibleContentBlockPages\Facades\FilamentFlexibleContentBlockPages::routes();
