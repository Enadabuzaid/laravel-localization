# Frontend integration

The package shares four Inertia props on every page (spread `SharedProps` into `HandleInertiaRequests::share()`):

| Prop | Example | Purpose |
|---|---|---|
| `locale` | `"ar"` | Active locale |
| `dir` | `"rtl"` | Text direction |
| `locales` | `[{code:'en',label:'English'},{code:'ar',label:'العربية'}]` | Options for the language switcher |
| `translations` | `{ ui: { footer_rights: '...' } }` | The configured lang groups, merged over the fallback locale |

`translations` is an Inertia v3 **once prop** named `translations.<locale>`:

- The first visit sends it. Later visits skip it (the client sends `X-Inertia-Except-Once-Props`).
- Switching language changes the key, so the new locale is sent once.
- With older Inertia versions it falls back to a plain lazy prop.

## React

Use [`enadstack-ui-react`](../../../enadstack-ui-react) (`use-translation`, `language-switcher`, auth layouts):

```tsx
const { t } = useTranslation();
t('ui.footer_rights');
t('auth.throttle', { seconds: 30 });
```

## Root view

Set the direction on the first (server rendered) page load:

```blade
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app(\Enadstack\Localization\Support\Locales::class)->currentDirection() }}">
```

The frontend keeps `lang` and `dir` in sync afterwards, without a reload, when the language changes.

## Switching language from the client

`POST /locale` with `{ locale: 'en' }`. With Inertia: `router.post('/locale', { locale: 'en' })`. The response redirects
back, so the page re-renders in the new language.
