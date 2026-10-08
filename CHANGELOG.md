# Changelog

All notable changes to this package are documented here.

## [Unreleased]

### Added
- `SetLocale` middleware, registered globally (before Inertia builds its shared props).
- `POST /locale` switch route (`locale.update`) that stores the choice in an unencrypted cookie.
- `SharedProps` for Inertia: `locale`, `dir`, `locales` and `translations` (an Inertia v3 once prop keyed by locale).
- `Locales` service (supported codes, direction, cookie settings, translation groups).
- Publishable config (`localization-config`) and lang files (`localization-lang`) for `en` and `ar`:
  `validation`, `passwords`, `pagination`, `ui`.
- Tests for the middleware, route, shared props and `en`/`ar` key + placeholder parity.
