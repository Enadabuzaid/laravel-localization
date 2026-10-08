# Configuration

Publish the file with `php artisan vendor:publish --tag=localization-config`, then edit `config/localization.php`.

## `locales`

```php
'locales' => [
    'en' => ['label' => 'English', 'dir' => 'ltr'],
    'ar' => ['label' => 'العربية', 'dir' => 'rtl'],
],
```

- The key is the locale code. It needs a matching `lang/<code>/` folder in the application.
- `label` is shown in the language switcher. `dir` is `ltr` or `rtl` and drives `<html dir>` and the `dir` Inertia prop.
- The first request without a cookie uses `config('app.locale')` (`APP_LOCALE`), falling back to `app.fallback_locale`.

## `cookie`

| Key | Default | Notes |
|---|---|---|
| `name` | `locale` | Cookie that remembers the visitor's choice. |
| `minutes` | `525600` | One year. |

A cookie is used instead of the session because logging out invalidates the session. The cookie is excluded
from encryption (it holds only a locale code), which lets the locale be applied before the `web` middleware group.

## `register_middleware`

`true` prepends `SetLocale` to the global middleware stack. Reason: Inertia sorts its middleware right after
`StartSession`, ahead of the `web` group, so the locale must be set before Inertia builds its shared props.
Set it to `false` only if you register `Enadstack\Localization\Http\Middleware\SetLocale` yourself (and keep
it before `HandleInertiaRequests`).

## `route`

| Key | Default |
|---|---|
| `enabled` | `true` |
| `uri` | `locale` |
| `name` | `locale.update` |
| `middleware` | `['web']` |

The route accepts `POST` with `locale=<code>`, validates it against `locales`, queues the cookie and redirects back.
An unsupported code returns a validation error on `locale` and sets no cookie.

## `translations`

| Key | Default | Notes |
|---|---|---|
| `groups` | `['ui']` | Lang files (without `.php`) sent to the frontend. |
| `ttl` | `60` | Minutes the client remembers the once prop. |

Never list `validation`, `passwords` or `pagination`: everything in a listed group is visible in the page source.

## Environment

```env
APP_LOCALE=ar
APP_FALLBACK_LOCALE=en
```
