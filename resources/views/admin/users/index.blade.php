@extends('admin.layout')

@section('title', 'Usuarios administrativos')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Administración
                </span>

                <h1>
                    Usuarios y permisos
                </h1>

                <p>
                    Gestiona quién puede acceder al panel
                    y qué módulos puede administrar.
                </p>
            </div>

            <a
                href="{{ route(
                    'admin.users.create'
                ) }}"
                class="nv-button nv-button-primary"
            >
                Nuevo usuario
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="nv-admin-panel">
            <div class="nv-admin-panel-header">
                <div>
                    <h2>
                        Usuarios administrativos
                    </h2>

                    <p>
                        {{ $users->count() }}
                        usuario(s) administrado(s).
                    </p>
                </div>
            </div>

            @if ($users->isEmpty())
                <div class="nv-admin-empty">
                    No hay usuarios administrativos.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Acceso</th>
                                <th>Creado</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $managedUser)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $managedUser->name }}
                                        </strong>

                                        @if (
                                            auth()->id()
                                            === $managedUser->id
                                        )
                                            <div>
                                                <small>
                                                    Tu cuenta
                                                </small>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $managedUser->email }}
                                    </td>

                                    <td>
                                        <span
                                            class="
                                                badge
                                                rounded-pill
                                                text-bg-dark
                                            "
                                        >
                                            {{ $managedUser->adminRoleLabel() }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($managedUser->is_admin)
                                            <span
                                                class="
                                                    badge
                                                    rounded-pill
                                                    text-bg-success
                                                "
                                            >
                                                Activo
                                            </span>
                                        @else
                                            <span
                                                class="
                                                    badge
                                                    rounded-pill
                                                    text-bg-secondary
                                                "
                                            >
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $managedUser->created_at
                                            ?->format('d/m/Y') }}
                                    </td>

                                    <td>
                                        <div
                                            class="
                                                d-flex
                                                gap-2
                                                justify-content-end
                                            "
                                        >
                                            <a
                                                href="{{ route(
                                                    'admin.users.edit',
                                                    $managedUser
                                                ) }}"
                                                class="nv-admin-action-link"
                                            >
                                                Editar
                                            </a>

                                            @if (
                                                auth()->id()
                                                !== $managedUser->id
                                            )
                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.users.toggle-access',
                                                        $managedUser
                                                    ) }}"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="
                                                            btn
                                                            btn-sm
                                                            btn-outline-secondary
                                                        "
                                                    >
                                                        {{ $managedUser->is_admin
                                                            ? 'Desactivar'
                                                            : 'Activar' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection