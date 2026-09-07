<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_all_admin_areas(): void
    {
        $admin = $this->createAdmin(
            User::ROLE_SUPER_ADMIN,
            'super@example.com'
        );

        $this
            ->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this
            ->actingAs($admin)
            ->get(route('admin.products.index'))
            ->assertOk();

        $this
            ->actingAs($admin)
            ->get(route('admin.categories.index'))
            ->assertOk();

        $this
            ->actingAs($admin)
            ->get(route('admin.brands.index'))
            ->assertOk();

        $this
            ->actingAs($admin)
            ->get(route('admin.quotes.index'))
            ->assertOk();

        $this
            ->actingAs($admin)
            ->get(route('admin.contact-settings.edit'))
            ->assertOk();

        $this
            ->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk();
    }

    public function test_commercial_user_only_accesses_commercial_areas(): void
    {
        $commercial = $this->createAdmin(
            User::ROLE_COMMERCIAL,
            'commercial@example.com'
        );

        $this
            ->actingAs($commercial)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this
            ->actingAs($commercial)
            ->get(route('admin.quotes.index'))
            ->assertOk();

        $this
            ->actingAs($commercial)
            ->get(route('admin.contact-settings.edit'))
            ->assertOk();

        $this
            ->actingAs($commercial)
            ->get(route('admin.products.index'))
            ->assertForbidden();

        $this
            ->actingAs($commercial)
            ->get(route('admin.categories.index'))
            ->assertForbidden();

        $this
            ->actingAs($commercial)
            ->get(route('admin.brands.index'))
            ->assertForbidden();

        $this
            ->actingAs($commercial)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_catalog_user_only_accesses_catalog_areas(): void
    {
        $catalog = $this->createAdmin(
            User::ROLE_CATALOG,
            'catalog@example.com'
        );

        $this
            ->actingAs($catalog)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this
            ->actingAs($catalog)
            ->get(route('admin.products.index'))
            ->assertOk();

        $this
            ->actingAs($catalog)
            ->get(route('admin.categories.index'))
            ->assertOk();

        $this
            ->actingAs($catalog)
            ->get(route('admin.brands.index'))
            ->assertOk();

        $this
            ->actingAs($catalog)
            ->get(route('admin.quotes.index'))
            ->assertForbidden();

        $this
            ->actingAs($catalog)
            ->get(route('admin.contact-settings.edit'))
            ->assertForbidden();

        $this
            ->actingAs($catalog)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_editor_can_only_access_dashboard_for_now(): void
    {
        $editor = $this->createAdmin(
            User::ROLE_EDITOR,
            'editor@example.com'
        );

        $this
            ->actingAs($editor)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this
            ->actingAs($editor)
            ->get(route('admin.products.index'))
            ->assertForbidden();

        $this
            ->actingAs($editor)
            ->get(route('admin.quotes.index'))
            ->assertForbidden();

        $this
            ->actingAs($editor)
            ->get(route('admin.contact-settings.edit'))
            ->assertForbidden();

        $this
            ->actingAs($editor)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_non_admin_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'name' => 'Usuario normal',
            'email' => 'normal@example.com',
            'password' => 'SecurePassword123',
            'is_admin' => false,
            'admin_role' => null,
        ]);

        $this
            ->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_super_admin_can_create_administrative_user(): void
    {
        $admin = $this->createAdmin(
            User::ROLE_SUPER_ADMIN,
            'super@example.com'
        );

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.users.store'),
                [
                    'name' =>
                        'Usuario Comercial',

                    'email' =>
                        'COMMERCIAL.NEW@EXAMPLE.COM',

                    'admin_role' =>
                        User::ROLE_COMMERCIAL,

                    'password' =>
                        'SecurePassword123',

                    'password_confirmation' =>
                        'SecurePassword123',
                ]
            );

        $user = User::query()
            ->where(
                'email',
                'commercial.new@example.com'
            )
            ->firstOrFail();

        $response->assertRedirect(
            route(
                'admin.users.edit',
                $user
            )
        );

        $this->assertTrue(
            $user->is_admin
        );

        $this->assertSame(
            User::ROLE_COMMERCIAL,
            $user->admin_role
        );

        $this->assertTrue(
            Hash::check(
                'SecurePassword123',
                $user->password
            )
        );
    }

    public function test_non_super_admin_cannot_create_administrative_users(): void
    {
        $commercial = $this->createAdmin(
            User::ROLE_COMMERCIAL,
            'commercial@example.com'
        );

        $this
            ->actingAs($commercial)
            ->post(
                route('admin.users.store'),
                [
                    'name' =>
                        'Intento Escalada',

                    'email' =>
                        'escalation@example.com',

                    'admin_role' =>
                        User::ROLE_SUPER_ADMIN,

                    'password' =>
                        'SecurePassword123',

                    'password_confirmation' =>
                        'SecurePassword123',
                ]
            )
            ->assertForbidden();

        $this->assertDatabaseMissing(
            'users',
            [
                'email' =>
                    'escalation@example.com',
            ]
        );
    }

    public function test_super_admin_can_change_another_admin_role(): void
    {
        $superAdmin = $this->createAdmin(
            User::ROLE_SUPER_ADMIN,
            'super@example.com'
        );

        $user = $this->createAdmin(
            User::ROLE_COMMERCIAL,
            'target@example.com'
        );

        $response = $this
            ->actingAs($superAdmin)
            ->put(
                route(
                    'admin.users.update',
                    $user
                ),
                [
                    'name' =>
                        $user->name,

                    'email' =>
                        $user->email,

                    'admin_role' =>
                        User::ROLE_CATALOG,

                    'password' =>
                        '',

                    'password_confirmation' =>
                        '',
                ]
            );

        $response->assertRedirect(
            route(
                'admin.users.edit',
                $user
            )
        );

        $this->assertSame(
            User::ROLE_CATALOG,
            $user->fresh()->admin_role
        );
    }

    public function test_super_admin_cannot_demote_himself(): void
    {
        $admin = $this->createAdmin(
            User::ROLE_SUPER_ADMIN,
            'super@example.com'
        );

        $response = $this
            ->actingAs($admin)
            ->from(
                route(
                    'admin.users.edit',
                    $admin
                )
            )
            ->put(
                route(
                    'admin.users.update',
                    $admin
                ),
                [
                    'name' =>
                        $admin->name,

                    'email' =>
                        $admin->email,

                    'admin_role' =>
                        User::ROLE_COMMERCIAL,

                    'password' =>
                        '',

                    'password_confirmation' =>
                        '',
                ]
            );

        $response
            ->assertRedirect(
                route(
                    'admin.users.edit',
                    $admin
                )
            )
            ->assertSessionHasErrors(
                'admin_role'
            );

        $admin->refresh();

        $this->assertSame(
            User::ROLE_SUPER_ADMIN,
            $admin->admin_role
        );

        $this->assertTrue(
            $admin->is_admin
        );
    }

    public function test_super_admin_cannot_disable_own_account(): void
    {
        $admin = $this->createAdmin(
            User::ROLE_SUPER_ADMIN,
            'super@example.com'
        );

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.users.toggle-access',
                    $admin
                )
            );

        $response
            ->assertRedirect(
                route('admin.users.index')
            )
            ->assertSessionHasErrors(
                'user'
            );

        $this->assertTrue(
            $admin->fresh()->is_admin
        );
    }

    public function test_super_admin_can_disable_and_reactivate_another_admin(): void
    {
        $admin = $this->createAdmin(
            User::ROLE_SUPER_ADMIN,
            'super@example.com'
        );

        $commercial = $this->createAdmin(
            User::ROLE_COMMERCIAL,
            'commercial@example.com'
        );

        $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.users.toggle-access',
                    $commercial
                )
            )
            ->assertRedirect(
                route('admin.users.index')
            );

        $commercial->refresh();

        $this->assertFalse(
            $commercial->is_admin
        );

        $this->assertSame(
            User::ROLE_COMMERCIAL,
            $commercial->admin_role
        );

        $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.users.toggle-access',
                    $commercial
                )
            )
            ->assertRedirect(
                route('admin.users.index')
            );

        $commercial->refresh();

        $this->assertTrue(
            $commercial->is_admin
        );

        $this->assertSame(
            User::ROLE_COMMERCIAL,
            $commercial->admin_role
        );
    }

    public function test_disabled_admin_cannot_login(): void
    {
        User::factory()->create([
            'name' =>
                'Comercial Inactivo',

            'email' =>
                'disabled@example.com',

            'password' =>
                'SecurePassword123',

            'is_admin' =>
                false,

            'admin_role' =>
                User::ROLE_COMMERCIAL,
        ]);

        $response = $this->post(
            route('admin.login.store'),
            [
                'email' =>
                    'disabled@example.com',

                'password' =>
                    'SecurePassword123',
            ]
        );

        $response
            ->assertSessionHasErrors(
                'email'
            );

        $this->assertGuest();
    }

    public function test_second_super_admin_can_demote_another_super_admin(): void
    {
        $first = $this->createAdmin(
            User::ROLE_SUPER_ADMIN,
            'first@example.com'
        );

        $second = $this->createAdmin(
            User::ROLE_SUPER_ADMIN,
            'second@example.com'
        );

        $response = $this
            ->actingAs($second)
            ->put(
                route(
                    'admin.users.update',
                    $first
                ),
                [
                    'name' =>
                        $first->name,

                    'email' =>
                        $first->email,

                    'admin_role' =>
                        User::ROLE_CATALOG,

                    'password' =>
                        '',

                    'password_confirmation' =>
                        '',
                ]
            );

        $response->assertRedirect(
            route(
                'admin.users.edit',
                $first
            )
        );

        $this->assertSame(
            User::ROLE_CATALOG,
            $first->fresh()->admin_role
        );

        $this->assertSame(
            User::ROLE_SUPER_ADMIN,
            $second->fresh()->admin_role
        );
    }

    public function test_legacy_admin_without_role_is_treated_as_super_admin(): void
    {
        $admin = User::factory()->create([
            'name' =>
                'Administrador Legacy',

            'email' =>
                'legacy@example.com',

            'password' =>
                'SecurePassword123',

            'is_admin' =>
                true,

            'admin_role' =>
                null,
        ]);

        $this->assertTrue(
            $admin->isSuperAdmin()
        );

        $this->assertSame(
            User::ROLE_SUPER_ADMIN,
            $admin->effectiveAdminRole()
        );

        $this
            ->actingAs($admin)
            ->get(
                route('admin.users.index')
            )
            ->assertOk();
    }

    private function createAdmin(
        string $role,
        string $email
    ): User {
        return User::factory()->create([
            'name' =>
                'Administrador '.$role,

            'email' =>
                $email,

            'password' =>
                'SecurePassword123',

            'is_admin' =>
                true,

            'admin_role' =>
                $role,
        ]);
    }
}