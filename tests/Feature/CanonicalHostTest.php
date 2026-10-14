<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CanonicalHostTest extends TestCase
{
    public function test_www_redirects_permanently_to_the_configured_origin_and_preserves_the_query(): void
    {
        config(['app.env' => 'production', 'app.url' => 'https://natviewer.com']);

        $this->get('https://www.natviewer.com/es?utm_source=google')
            ->assertStatus(301)
            ->assertRedirect('https://natviewer.com/es?utm_source=google');
    }

    public function test_temporary_cloudways_domain_redirects_to_the_configured_origin(): void
    {
        config(['app.env' => 'production', 'app.url' => 'https://natviewer.com']);

        $this->get('https://phplaravel-1655134-6727369.cloudwaysapps.com/en')
            ->assertStatus(301)->assertRedirect('https://natviewer.com/en');
    }

    public function test_canonical_host_serves_robots_without_a_redirect(): void
    {
        config(['app.env' => 'production', 'app.url' => 'https://natviewer.com']);

        $this->get('https://natviewer.com/robots.txt')->assertOk()
            ->assertSee('Sitemap: https://natviewer.com/sitemap.xml', false);
    }

    public function test_local_preview_is_not_redirected_to_production(): void
    {
        config(['app.env' => 'testing', 'app.url' => 'https://natviewer.com']);

        $this->get('http://localhost/robots.txt')->assertOk();
    }

    public function test_health_check_is_available_with_an_internal_host(): void
    {
        config(['app.env' => 'production', 'app.url' => 'https://natviewer.com']);

        $this->get('http://localhost/up')->assertOk();
    }

    public function test_state_changing_requests_are_not_redirected_or_converted_to_get(): void
    {
        config(['app.env' => 'production', 'app.url' => 'https://natviewer.com']);
        Route::post('/canonical-test', fn () => response()->noContent());

        $this->post('https://www.natviewer.com/canonical-test')->assertNoContent();
    }
}
