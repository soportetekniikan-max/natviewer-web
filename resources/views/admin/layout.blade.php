<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="robots"
        content="noindex, nofollow"
    >

    <title>
        @yield('title', 'Administración') | Natviewer
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="nv-admin-body">
    @auth
        <header class="nv-admin-header">
            <div class="container-fluid">
                <div class="nv-admin-header-inner">
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="nv-admin-brand"
                    >
                        <img
                            src="{{ asset('images/logo-natviewer-white.png') }}"
                            alt="Natviewer"
                        >

                        <span>Admin</span>
                    </a>

                    <div class="nv-admin-user">
                        <span>
                            {{ auth()->user()->name }}
                        </span>

                        <form
                            method="POST"
                            action="{{ route('admin.logout') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="nv-admin-logout"
                            >
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
    @endauth

    <main class="nv-admin-main">
        @yield('content')
    </main>
</body>
</html>