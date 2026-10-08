<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Available Locales
    |--------------------------------------------------------------------------
    |
    | The languages a visitor can switch between. Every locale needs a matching
    | directory in the application's "lang" folder. "dir" is the text direction
    | (ltr / rtl) and "label" is what the language switcher shows.
    |
    */

    'locales' => [
        'en' => ['label' => 'English', 'dir' => 'ltr'],
        'ar' => ['label' => 'العربية', 'dir' => 'rtl'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Locale Cookie
    |--------------------------------------------------------------------------
    |
    | The chosen locale is remembered in a cookie (a session would be wiped on
    | logout). The cookie is excluded from encryption so the locale can be set
    | before any other middleware runs. It holds no sensitive data.
    |
    */

    'cookie' => [
        'name' => 'locale',
        'minutes' => 60 * 24 * 365,
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | When enabled the package prepends its SetLocale middleware to the global
    | stack, so the locale is set before Inertia builds its shared props. Turn
    | it off if you prefer to register the middleware yourself.
    |
    */

    'register_middleware' => true,

    /*
    |--------------------------------------------------------------------------
    | Language Switch Route
    |--------------------------------------------------------------------------
    */

    'route' => [
        'enabled' => true,
        'uri' => 'locale',
        'name' => 'locale.update',
        'middleware' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Frontend Translations
    |--------------------------------------------------------------------------
    |
    | Lang files (groups) that are shared with the frontend as Inertia props.
    | Server-only files such as "validation" must not be listed here. The
    | translations are sent once per locale and remembered by the client for
    | "ttl" minutes.
    |
    */

    'translations' => [
        'groups' => ['ui'],
        'ttl' => 60,
    ],

];
