@extends('admin.layout')

@section('title', 'Nuevo usuario administrativo')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Usuarios
                </span>

                <h1>
                    Nuevo usuario administrativo
                </h1>

                <p>
                    Crea una cuenta y asigna el nivel
                    de acceso correspondiente.
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
                'admin.users.store'
            ) }}"
            class="nv-admin-product-form"
        >
            @csrf

            <section class="nv-admin-form-card">
                <div class="nv-admin-form-card-header">
                    <div>
                        <h2>
                            Información del usuario
                        </h2>
                    </div>
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
                            value="{{ old('name') }}"
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
                            value="{{ old('email') }}"
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
                            <option value="">
                                Selecciona un rol
                            </option>

                            @foreach ($roleLabels as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old('admin_role')
                                        === $value
                                    )
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="nv-admin-field">
                        <label>
                            Acceso
                        </label>

                        <div>
                            <span
                                class="
                                    badge
                                    rounded-pill
                                    text-bg-success
                                "
                            >
                                Activo al crear
                            </span>
                        </div>
                    </div>

                    <div class="nv-admin-field">
                        <label for="password">
                            Contraseña *
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            autocomplete="new-password"
                            required
                        >

                        <small>
                            Mínimo 12 caracteres,
                            incluyendo mayúsculas,
                            minúsculas y números.
                        </small>
                    </div>

                    <div class="nv-admin-field">
                        <label for="password_confirmation">
                            Confirmar contraseña *
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            autocomplete="new-password"
                            required
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
                    Crear usuario
                </button>
            </div>
        </form>
    </div>
@endsection