<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_is_accessible(): void
    {
        $response = $this->get('/admin/login');

        $response
            ->assertStatus(200)
            ->assertViewIs('admin.auth.login');
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Administrador Test',
            'email' => 'admin@example.com',
            'password' => 'password-seguro',
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password-seguro',
        ]);

        $response->assertRedirect('/admin');

        $this->assertAuthenticatedAs($admin);

        $dashboard = $this->get('/admin');

        $dashboard
            ->assertStatus(200)
            ->assertViewIs('admin.dashboard')
            ->assertViewHas('stats')
            ->assertViewHas('latestQuotes');
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::create([
            'name' => 'Administrador Test',
            'email' => 'admin@example.com',
            'password' => 'password-correcto',
            'is_admin' => true,
        ]);

        $response = $this
            ->from('/admin/login')
            ->post('/admin/login', [
                'email' => 'admin@example.com',
                'password' => 'password-incorrecto',
            ]);

        $response
            ->assertRedirect('/admin/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_admin_user_cannot_login_to_admin_panel(): void
    {
        User::create([
            'name' => 'Usuario Normal',
            'email' => 'usuario@example.com',
            'password' => 'password-seguro',
            'is_admin' => false,
        ]);

        $response = $this
            ->from('/admin/login')
            ->post('/admin/login', [
                'email' => 'usuario@example.com',
                'password' => 'password-seguro',
            ]);

        $response
            ->assertRedirect('/admin/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_authenticated_non_admin_cannot_access_dashboard(): void
    {
        $user = User::create([
            'name' => 'Usuario Normal',
            'email' => 'usuario@example.com',
            'password' => 'password-seguro',
            'is_admin' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/admin');

        $response->assertForbidden();
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::create([
            'name' => 'Administrador Test',
            'email' => 'admin@example.com',
            'password' => 'password-seguro',
            'is_admin' => true,
        ]);

        $response = $this
            ->actingAs($admin)
            ->post('/admin/logout');

        $response->assertRedirect('/admin/login');

        $this->assertGuest();
    }
}