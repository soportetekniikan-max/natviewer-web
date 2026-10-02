<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->where(
                function ($query) {
                    $query
                        ->where(
                            'is_admin',
                            true
                        )
                        ->orWhereNotNull(
                            'admin_role'
                        );
                }
            )
            ->orderByDesc('is_admin')
            ->orderBy('name')
            ->get();

        return view(
            'admin.users.index',
            [
                'users' => $users,

                'roleLabels' =>
                    User::adminRoleLabels(),
            ]
        );
    }

    public function create(): View
    {
        return view(
            'admin.users.create',
            [
                'roleLabels' =>
                    User::adminRoleLabels(),
            ]
        );
    }

    public function store(
        StoreAdminUserRequest $request
    ): RedirectResponse {
        $validated =
            $request->validated();

        $user = User::create([
            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'password' =>
                $validated['password'],

            'is_admin' =>
                true,

            'admin_role' =>
                $validated['admin_role'],

            'email_verified_at' =>
                now(),
        ]);

        return redirect()
            ->route(
                'admin.users.edit',
                $user
            )
            ->with(
                'success',
                'Usuario administrativo creado correctamente.'
            );
    }

    public function edit(
        User $user
    ): View {
        $this->ensureManagedUser(
            $user
        );

        return view(
            'admin.users.edit',
            [
                'managedUser' =>
                    $user,

                'roleLabels' =>
                    User::adminRoleLabels(),
            ]
        );
    }

    public function update(
        UpdateAdminUserRequest $request,
        User $user
    ): RedirectResponse {
        $this->ensureManagedUser(
            $user
        );

        $validated =
            $request->validated();

        $currentUser =
            $request->user();

        if (
            $currentUser
            && $currentUser->is($user)
            && $validated['admin_role']
                !== User::ROLE_SUPER_ADMIN
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'admin_role' =>
                        'No puedes quitarte a ti mismo el rol de Super Admin. Inicia sesión con otro Super Admin para realizar ese cambio.',
                ]);
        }

        if (
            $this->isLastActiveSuperAdmin(
                $user
            )
            && $validated['admin_role']
                !== User::ROLE_SUPER_ADMIN
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'admin_role' =>
                        'No puedes cambiar el rol del último Super Admin activo.',
                ]);
        }

        $user->name =
            $validated['name'];

        $user->email =
            $validated['email'];

        $user->admin_role =
            $validated['admin_role'];

        if (
            ! empty(
                $validated['password']
            )
        ) {
            $user->password =
                $validated['password'];
        }

        $user->save();

        return redirect()
            ->route(
                'admin.users.edit',
                $user
            )
            ->with(
                'success',
                'Usuario actualizado correctamente.'
            );
    }

    public function toggleAccess(
        Request $request,
        User $user
    ): RedirectResponse {
        $this->ensureManagedUser(
            $user
        );

        $currentUser =
            $request->user();

        if (
            $currentUser
            && $currentUser->is($user)
        ) {
            return redirect()
                ->route(
                    'admin.users.index'
                )
                ->withErrors([
                    'user' =>
                        'No puedes desactivar tu propia cuenta administrativa.',
                ]);
        }

        if (
            $user->is_admin
            && $this->isLastActiveSuperAdmin(
                $user
            )
        ) {
            return redirect()
                ->route(
                    'admin.users.index'
                )
                ->withErrors([
                    'user' =>
                        'No puedes desactivar el último Super Admin activo.',
                ]);
        }

        if ($user->is_admin) {
            $user->is_admin =
                false;
        } else {
            if (! $user->admin_role) {
                $user->admin_role =
                    User::ROLE_EDITOR;
            }

            $user->is_admin =
                true;
        }

        $user->save();

        return redirect()
            ->route(
                'admin.users.index'
            )
            ->with(
                'success',
                $user->is_admin
                    ? 'Acceso administrativo activado.'
                    : 'Acceso administrativo desactivado.'
            );
    }

    private function ensureManagedUser(
        User $user
    ): void {
        if (
            ! $user->is_admin
            && ! $user->admin_role
        ) {
            abort(404);
        }
    }

    private function isLastActiveSuperAdmin(
        User $user
    ): bool {
        if (
            ! $user->is_admin
            || ! $user->isSuperAdmin()
        ) {
            return false;
        }

        $count = User::query()
            ->where(
                'is_admin',
                true
            )
            ->where(
                function ($query) {
                    $query
                        ->where(
                            'admin_role',
                            User::ROLE_SUPER_ADMIN
                        )
                        ->orWhereNull(
                            'admin_role'
                        );
                }
            )
            ->count();

        return $count <= 1;
    }
}