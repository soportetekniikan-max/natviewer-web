<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN =
        'super_admin';

    public const ROLE_COMMERCIAL =
        'commercial';

    public const ROLE_CATALOG =
        'catalog';

    public const ROLE_EDITOR =
        'editor';

    public const ADMIN_ROLES = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_COMMERCIAL,
        self::ROLE_CATALOG,
        self::ROLE_EDITOR,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'admin_role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' =>
                'datetime',

            'password' =>
                'hashed',

            'is_admin' =>
                'boolean',
        ];
    }

    public static function adminRoleLabels(): array
    {
        return [
            self::ROLE_SUPER_ADMIN =>
                'Super Admin',

            self::ROLE_COMMERCIAL =>
                'Comercial',

            self::ROLE_CATALOG =>
                'Catálogo',

            self::ROLE_EDITOR =>
                'Editor',
        ];
    }

    public function effectiveAdminRole(): ?string
    {
        if (! $this->is_admin) {
            return null;
        }

        /*
         * Compatibilidad con administradores antiguos
         * o tests existentes creados sin admin_role.
         *
         * La migración convierte los administradores
         * reales existentes a super_admin.
         */
        return $this->admin_role
            ?: self::ROLE_SUPER_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->effectiveAdminRole()
            === self::ROLE_SUPER_ADMIN;
    }

    public function hasAdminRole(
        string ...$roles
    ): bool {
        $role =
            $this->effectiveAdminRole();

        if ($role === null) {
            return false;
        }

        if (
            $role === self::ROLE_SUPER_ADMIN
        ) {
            return true;
        }

        return in_array(
            $role,
            $roles,
            true
        );
    }

    public function canAccessAdminArea(
        string $area
    ): bool {
        if (! $this->is_admin) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        $role =
            $this->effectiveAdminRole();

        return match ($area) {
            'catalog' =>
                $role === self::ROLE_CATALOG,

            'commercial' =>
                $role === self::ROLE_COMMERCIAL,

            'editor' =>
                $role === self::ROLE_EDITOR,

            'users' =>
                false,

            default =>
                false,
        };
    }

    public function adminRoleLabel(): string
    {
        $role =
            $this->admin_role;

        if (
            ! $role
            && $this->is_admin
        ) {
            $role =
                self::ROLE_SUPER_ADMIN;
        }

        return self::adminRoleLabels()[$role]
            ?? 'Sin rol';
    }
}