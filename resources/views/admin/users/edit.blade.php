@extends('admin.layout')

@section('title', 'Editar usuario')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Usuarios
                </span>

                <h1>
                    Editar usuario
                </h1>

                <p>
                    {{ $managedUser->name }}
                </p>
            </div>

            <a
                href="{{ route(
                    'admin.users.index'
                ) }}"
                class="nv-button nv-button-outline"
            >
                Volver
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

        <form
            method="POST"
            action="{{ route(
                'admin.users.update',
                $managedUser
            ) }}"
            class="nv-admin-product-form"
        >
            @csrf
            @method('PUT')

            <section class="nv-admin-form-card">
                <div class="nv-admin-form-card-header">
                    <div>
                        <h2>
                            Información del usuario
                        </h2>
                    </div>

                    @if ($managedUser->is_admin)
                        <span
                            class="
                                badge
                                rounded-pill
                                text-bg-success
                            "
                        >
                            Acceso activo
                        </span>
                    @else
                        <span
                            class="
                                badge
                                rounded-pill
                                text-bg-secondary
                            "
                        >
                            Acceso inactivo
                        </span>
                    @endif
                </div>

                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label for="name">
                            Nombre *
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            maxlength="255"
                            value="{{ old(
                                'name',
                                $managedUser->name
                            ) }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label for="email">
                            Email *
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            maxlength="255"
                            value="{{ old(
                                'email',
                                $managedUser->email
                            ) }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label for="admin_role">
                            Rol *
                        </label>

                        <select
                            id="admin_role"
                            name="admin_role"
                            class="form-select"
                            required
                        >
                            @foreach ($roleLabels as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'admin_role',
                                            $managedUser->admin_role
                                                ?: \App\Models\User::ROLE_SUPER_ADMIN
                                        )
                                        === $value
                                    )
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        @if (
                            auth()->id()
                            === $managedUser->id
                        )
                            <small>
                                Tu propio rol de Super Admin
                                está protegido.
                            </small>
                        @endif
                    </div>

                    <div class="nv-admin-field">
                        <label>
                            Estado de acceso
                        </label>

                        <div>
                            {{ $managedUser->is_admin
                                ? 'Activo'
                                : 'Inactivo' }}
                        </div>
                    </div>

                    <div class="nv-admin-field">
                        <label for="password">
                            Nueva contraseña
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            autocomplete="new-password"
                        >

                        <small>
                            Déjala vacía para conservar
                            la contraseña actual.
                        </small>
                    </div>

                    <div class="nv-admin-field">
                        <label for="password_confirmation">
                            Confirmar nueva contraseña
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            autocomplete="new-password"
                        >
                    </div>
                </div>
            </section>

            <div class="nv-admin-form-actions">
                <a
                    href="{{ route(
                        'admin.users.index'
                    ) }}"
                    class="nv-button nv-button-outline"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="nv-button nv-button-primary"
                >
                    Guardar usuario
                </button>
            </div>
        </form>
    </div>
@endsection