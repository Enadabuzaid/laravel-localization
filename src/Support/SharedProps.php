<?php

declare(strict_types=1);

namespace Enadstack\Localization\Support;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;
use Inertia\OnceProp;

/**
 * The props every Inertia page needs to render in the active language.
 *
 * Spread it into your HandleInertiaRequests::share():
 *
 *     ...app(SharedProps::class)->toArray(),
 */
final class SharedProps
{
    public function __construct(private readonly Locales $locales) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $locale = App::getLocale();

        return [
            'locale' => $locale,
            'dir' => $this->locales->direction($locale),
            'locales' => collect($this->locales->all())
                ->map(fn (array $locale, string $code): array => ['code' => $code, 'label' => $locale['label']])
                ->values()
                ->all(),
            'translations' => $this->translationsProp($locale),
        ];
    }

    /**
     * The lang groups of one locale, merged over the fallback locale.
     *
     * @return array<string, array<string, mixed>>
     */
    public function translations(string $locale): array
    {
        $loader = Lang::getLoader();
        $translations = [];

        foreach ($this->locales->translationGroups() as $group) {
            $translations[$group] = array_replace_recursive(
                $loader->load(config('app.fallback_locale'), $group),
                $loader->load($locale, $group),
            );
        }

        return $translations;
    }

    /**
     * Sent once per locale with Inertia v3, so the client keeps it between visits.
     */
    private function translationsProp(string $locale): mixed
    {
        $resolve = fn (): array => $this->translations($locale);

        // OnceProp only exists in Inertia v3; older versions get a plain lazy prop.
        if (!class_exists(OnceProp::class)) {
            return $resolve;
        }

        return Inertia::once($resolve)
            ->as('translations.'.$locale)
            ->until(now()->addMinutes($this->locales->translationTtlMinutes()));
    }
}
