<?php

declare(strict_types=1);

namespace Enadstack\Localization;

use Enadstack\Localization\Http\Middleware\SetLocale;
use Enadstack\Localization\Support\Locales;
use Enadstack\Localization\Support\SharedProps;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/localization.php', 'localization');

        $this->app->singleton(Locales::class);
        $this->app->singleton(SharedProps::class);

        // The locale cookie holds no secret; leaving it unencrypted lets the
        // locale be applied before the web middleware group runs.
        EncryptCookies::except($this->app->make(Locales::class)->cookieName());
    }

    public function boot(): void
    {
        $this->registerPublishing();
        $this->registerMiddleware();

        if (config('localization.route.enabled')) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }
    }

    private function registerMiddleware(): void
    {
        if (!config('localization.register_middleware')) {
            return;
        }

        $kernel = $this->app->make(Kernel::class);

        if (method_exists($kernel, 'prependMiddleware')) {
            $kernel->prependMiddleware(SetLocale::class);
        }
    }

    private function registerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/localization.php' => config_path('localization.php'),
        ], 'localization-config');

        $this->publishes([
            __DIR__.'/../resources/lang' => $this->app->langPath(),
        ], 'localization-lang');
    }
}
