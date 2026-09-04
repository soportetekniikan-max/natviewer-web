@extends('admin.layout')

@section('title', 'Iniciar sesión')

@section('content')
    <section class="nv-admin-login">
        <div class="nv-admin-login-card">
            <div class="nv-admin-login-brand">
                <img
                    src="{{ asset('images/logo-natviewer-white.png') }}"
                    alt="Natviewer"
                >

                <span>Panel administrativo</span>
            </div>

            <div class="nv-admin-login-content">
                <span class="nv-eyebrow">
                    Administración
                </span>

                <h1>Iniciar sesión</h1>

                <p>
                    Accede al panel de gestión de Natviewer.
                </p>

                <form
                    method="POST"
                    action="{{ route('admin.login.store') }}"
                    class="nv-admin-login-form"
                >
                    @csrf

                    <div>
                        <label for="email">
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            autofocus
                            required
                            class="form-control @error('email') is-invalid @enderror"
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div>
                        <label for="password">
                            Contraseña
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            required
                            class="form-control @error('password') is-invalid @enderror"
                        >

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <label class="nv-admin-remember">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                        >

                        <span>Recordarme</span>
                    </label>

                    <button
                        type="submit"
                        class="nv-button nv-button-primary w-100"
                    >
                        Entrar al panel
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection