<?php

declare(strict_types=1);

namespace Enadstack\Localization\Support;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\App;

/**
 * Read access to the configured locales.
 */
final class Locales
{
    public function __construct(private readonly Repository $config) {}

    /**
     * @return array<string, array{label: string, dir: string}>
     */
    public function all(): array
    {
        return $this->config->get('localization.locales', []);
    }

    /**
     * @return list<string>
     */
    public function codes(): array
    {
        return array_keys($this->all());
    }

    public function isSupported(?string $code): bool
    {
        return is_string($code) && array_key_exists($code, $this->all());
    }

    public function direction(string $code): string
    {
        return $this->all()[$code]['dir'] ?? 'ltr';
    }

    /**
     * Text direction of the active locale, for the <html dir> attribute.
     */
    public function currentDirection(): string
    {
        return $this->direction(App::getLocale());
    }

    public function cookieName(): string
    {
        return (string) $this->config->get('localization.cookie.name', 'locale');
    }

    public function cookieMinutes(): int
    {
        return (int) $this->config->get('localization.cookie.minutes', 60 * 24 * 365);
    }

    /**
     * @return list<string>
     */
    public function translationGroups(): array
    {
        return array_values($this->config->get('localization.translations.groups', []));
    }

    public function translationTtlMinutes(): int
    {
        return (int) $this->config->get('localization.translations.ttl', 60);
    }
}
