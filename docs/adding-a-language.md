# Adding a language

1. Register it in `config/localization.php`:

   ```php
   'fr' => ['label' => 'Français', 'dir' => 'ltr'],
   ```

2. Create `lang/fr/` with the same files as `lang/en/` (`validation.php`, `passwords.php`, `pagination.php`, `ui.php`, plus your own groups).
3. Keep the **same keys and the same `:placeholders`** in every language. The package test pattern below fails the build when they drift:

   ```php
   $english = array_keys(Arr::dot(require lang_path('en/auth.php')));
   $french  = array_keys(Arr::dot(require lang_path('fr/auth.php')));
   expect($french)->toEqualCanonicalizing($english);
   ```

4. For right-to-left languages set `'dir' => 'rtl'`. Components must use logical classes (`ms-`, `me-`, `start-`, `end-`).
5. Add a font for the script in your root view if the system font is not enough (the host app decides, the package does not load fonts).
