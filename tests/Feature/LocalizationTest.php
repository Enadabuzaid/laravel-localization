<?php

declare(strict_types=1);

namespace Enadstack\Localization\Tests\Feature;

use Enadstack\Localization\Support\Locales;
use Enadstack\Localization\Support\SharedProps;
use Enadstack\Localization\Tests\TestCase;
use Illuminate\Testing\TestResponse;
use Inertia\OnceProp;
use PHPUnit\Framework\Attributes\Test;

class LocalizationTest extends TestCase
{
    #[Test]
    public function it_uses_the_app_default_locale_without_a_cookie(): void
    {
        $this->getJson('/_probe')->assertJson(['locale' => 'ar']);
    }

    #[Test]
    public function it_applies_a_supported_locale_cookie(): void
    {
        $this->probeWithCookie('en')
            ->assertJson(['locale' => 'en', 'message' => 'The x field is required.']);
    }

    #[Test]
    public function it_ignores_an_unsupported_locale_cookie(): void
    {
        $this->probeWithCookie('xx')
            ->assertJson(['locale' => 'ar']);
    }

    #[Test]
    public function the_switch_route_stores_the_locale_in_a_cookie(): void
    {
        $this->from('/_probe')
            ->post(route('locale.update'), ['locale' => 'en'])
            ->assertRedirect('/_probe')
            ->assertCookie('locale', 'en', false);
    }

    #[Test]
    public function the_switch_route_rejects_unknown_locales(): void
    {
        $this->from('/_probe')
            ->post(route('locale.update'), ['locale' => 'xx'])
            ->assertSessionHasErrors('locale')
            ->assertCookieMissing('locale');
    }

    #[Test]
    public function the_locale_cookie_is_excluded_from_encryption(): void
    {
        $response = $this->probeWithCookie('en');

        $this->assertSame('en', $response->json('cookie'));
    }

    #[Test]
    public function it_exposes_locale_direction_and_options(): void
    {
        $props = $this->app->make(SharedProps::class)->toArray();

        $this->assertSame('ar', $props['locale']);
        $this->assertSame('rtl', $props['dir']);
        $this->assertSame([['code' => 'en', 'label' => 'English'], ['code' => 'ar', 'label' => 'العربية']], $props['locales']);
    }

    #[Test]
    public function translations_are_an_inertia_once_prop_keyed_by_locale(): void
    {
        $prop = $this->app->make(SharedProps::class)->toArray()['translations'];

        $this->assertInstanceOf(OnceProp::class, $prop);
        $this->assertSame('translations.ar', $prop->getKey());
    }

    #[Test]
    public function it_loads_only_the_configured_groups_for_a_locale(): void
    {
        $translations = $this->app->make(SharedProps::class)->translations('ar');

        $this->assertSame(['ui'], array_keys($translations));
        $this->assertSame('تغيير اللغة', $translations['ui']['language']['switch']);
    }

    #[Test]
    public function locale_helpers_read_the_config(): void
    {
        $locales = $this->app->make(Locales::class);

        $this->assertSame('rtl', $locales->direction('ar'));
        $this->assertSame('ltr', $locales->direction('unknown'));
        $this->assertSame('rtl', $locales->currentDirection());
        $this->assertTrue($locales->isSupported('en'));
        $this->assertFalse($locales->isSupported(null));
    }

    private function probeWithCookie(string $locale): TestResponse
    {
        return $this->call('GET', '/_probe', [], ['locale' => $locale], [], ['HTTP_ACCEPT' => 'application/json']);
    }
}
