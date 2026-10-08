<?php

declare(strict_types=1);

use Enadstack\Localization\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::post(config('localization.route.uri'), LocaleController::class)
    ->middleware(config('localization.route.middleware'))
    ->name(config('localization.route.name'));
