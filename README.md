# Laravel Localization

Cookie based, RTL aware language switching for Laravel + Inertia apps. Pairs with the
`enadstack-ui-react` components (`use-translation`, `language-switcher`, auth layouts).

- `SetLocale` middleware (registered globally, reads an unencrypted `locale` cookie)
- `POST /locale` route (name `locale.update`) that remembers the choice for a year
- `SharedProps` for Inertia: `locale`, `dir`, `locales` and `translations` (sent once per locale)
- Ready `en` / `ar` lang files (`validation`, `passwords`, `pagination`, `ui`)

## Install

```bash
composer require enadstack/laravel-localization
php artisan vendor:publish --tag=localization-config   # config/localization.php
php artisan vendor:publish --tag=localization-lang     # lang/{en,ar}/...
```

Share the props in `app/Http/Middleware/HandleInertiaRequests.php`:

```php
use Enadstack\Localization\Support\SharedProps;

public function share(Request $request): array
{
    return [
        ...parent::share($request),
        ...app(SharedProps::class)->toArray(),
    ];
}
```

Set the default language with `APP_LOCALE` (the fallback is `APP_FALLBACK_LOCALE`).

## Configure (`config/localization.php`)

| Key | Purpose |
|---|---|
| `locales` | `code => ['label', 'dir']`, each needs a `lang/<code>` folder |
| `cookie` | cookie name and lifetime |
| `register_middleware` | prepend `SetLocale` globally (turn off to register it yourself) |
| `route` | enable, uri, name and middleware of the switch route |
| `translations.groups` | lang files sent to the frontend. **Never list `validation` or `passwords`.** |
| `translations.ttl` | minutes the client keeps the once prop |

To add a language: add it to `locales`, create `lang/<code>/*.php` with the same keys.
Keep key parity with a test (see the `TranslationFilesTest` idea in the host app).

## Why a global middleware?

Inertia sorts its middleware right after the session, ahead of the web group. The locale must be set
before Inertia builds its shared props, so `SetLocale` is prepended globally and the cookie is
excluded from encryption.

## Tests

```bash
composer install && composer test
```
