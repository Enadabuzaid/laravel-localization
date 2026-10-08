<?php

declare(strict_types=1);

namespace Enadstack\Localization\Tests\Feature;

use Enadstack\Localization\Tests\TestCase;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class LangFilesTest extends TestCase
{
    /**
     * @return array<string, array{string}>
     */
    public static function langGroups(): array
    {
        $groups = array_map(fn (string $path): string => basename($path, '.php'), glob(__DIR__.'/../../resources/lang/en/*.php'));

        return array_combine($groups, array_map(fn (string $group): array => [$group], $groups));
    }

    #[Test]
    #[DataProvider('langGroups')]
    public function every_language_defines_the_same_keys_as_english(string $group): void
    {
        $english = array_keys(Arr::dot(require __DIR__."/../../resources/lang/en/{$group}.php"));
        $arabic = array_keys(Arr::dot(require __DIR__."/../../resources/lang/ar/{$group}.php"));

        $this->assertEqualsCanonicalizing($english, $arabic);
    }

    #[Test]
    #[DataProvider('langGroups')]
    public function placeholders_match_between_languages(string $group): void
    {
        $english = Arr::dot(require __DIR__."/../../resources/lang/en/{$group}.php");
        $arabic = Arr::dot(require __DIR__."/../../resources/lang/ar/{$group}.php");

        foreach ($english as $key => $text) {
            preg_match_all('/:[a-z_]+/i', (string) $text, $expected);
            preg_match_all('/:[a-z_]+/i', (string) ($arabic[$key] ?? ''), $actual);

            $this->assertEqualsCanonicalizing($expected[0], $actual[0], "{$group}.{$key}");
        }
    }
}
