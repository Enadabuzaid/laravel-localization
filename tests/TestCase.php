<?php

declare(strict_types=1);

namespace Enadstack\Localization\Tests;

use Enadstack\Localization\LocalizationServiceProvider;
use Inertia\ServiceProvider as InertiaServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [InertiaServiceProvider::class, LocalizationServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
        $app['config']->set('app.locale', 'ar');
        $app['config']->set('app.fallback_locale', 'en');
        $app['config']->set('localization.translations.groups', ['ui']);

        $app->useLangPath(__DIR__.'/../resources/lang');
    }

    protected function defineRoutes($router): void
    {
        $router->middleware('web')->get('/_probe', fn () => response()->json([
            'locale' => app()->getLocale(),
            'cookie' => request()->cookie('locale'),
            'message' => __('validation.required', ['attribute' => 'x']),
        ]));
    }
}
