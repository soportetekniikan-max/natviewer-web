<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Administración') | Natviewer
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="bg-light">
    @auth
        <header
            class="navbar navbar-expand-lg navbar-dark shadow-sm"
            style="background-color: #122121;"
        >
            <div class="container-fluid px-4">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="navbar-brand d-flex align-items-center"
                >
                    <img
                        src="{{ asset(
                            'images/logo-natviewer-white.png'
                        ) }}"
                        alt="Natviewer"
                        style="
                            max-height: 38px;
                            width: auto;
                        "
                    >
                </a>

                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#adminNavigation"
                    aria-controls="adminNavigation"
                    aria-expanded="false"
                    aria-label="Abrir navegación"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div
                    class="collapse navbar-collapse"
                    id="adminNavigation"
                >
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="
                                    nav-link
                                    {{ request()->routeIs(
                                        'admin.dashboard'
                                    )
                                        ? 'active fw-semibold'
                                        : '' }}
                                "
                            >
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a
                                href="#"
                                class="
                                    nav-link
                                    dropdown-toggle
                                    {{ request()->routeIs(
                                        'admin.products.*',
                                        'admin.categories.*',
                                        'admin.brands.*'
                                    )
                                        ? 'active fw-semibold'
                                        : '' }}
                                "
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                Catálogo
                            </a>

                            <ul class="dropdown-menu shadow">
                                <li>
                                    <a
                                        href="{{ route(
                                            'admin.products.index'
                                        ) }}"
                                        class="
                                            dropdown-item
                                            {{ request()->routeIs(
                                                'admin.products.*'
                                            )
                                                ? 'active'
                                                : '' }}
                                        "
                                    >
                                        Productos
                                    </a>
                                </li>

                                <li>
                                    <a
                                        href="{{ route(
                                            'admin.categories.index'
                                        ) }}"
                                        class="
                                            dropdown-item
                                            {{ request()->routeIs(
                                                'admin.categories.*'
                                            )
                                                ? 'active'
                                                : '' }}
                                        "
                                    >
                                        Categorías
                                    </a>
                                </li>

                                <li>
                                    <a
                                        href="{{ route(
                                            'admin.brands.index'
                                        ) }}"
                                        class="
                                            dropdown-item
                                            {{ request()->routeIs(
                                                'admin.brands.*'
                                            )
                                                ? 'active'
                                                : '' }}
                                        "
                                    >
                                        Marcas
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a
                                href="#"
                                class="
                                    nav-link
                                    dropdown-toggle
                                    {{ request()->routeIs(
                                        'admin.quotes.*'
                                    )
                                        ? 'active fw-semibold'
                                        : '' }}
                                "
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                Comercial
                            </a>

                            <ul class="dropdown-menu shadow">
                                <li>
                                    <a
                                        href="{{ route(
                                            'admin.quotes.index'
                                        ) }}"
                                        class="
                                            dropdown-item
                                            {{ request()->routeIs(
                                                'admin.quotes.*'
                                            )
                                                ? 'active'
                                                : '' }}
                                        "
                                    >
                                        Cotizaciones
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a
                                href="#"
                                class="
                                    nav-link
                                    dropdown-toggle
                                    {{ request()->routeIs(
                                        'admin.contact-settings.*'
                                    )
                                        ? 'active fw-semibold'
                                        : '' }}
                                "
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                Configuración
                            </a>

                            <ul class="dropdown-menu shadow">
                                <li>
                                    <a
                                        href="{{ route(
                                            'admin.contact-settings.edit'
                                        ) }}"
                                        class="
                                            dropdown-item
                                            {{ request()->routeIs(
                                                'admin.contact-settings.*'
                                            )
                                                ? 'active'
                                                : '' }}
                                        "
                                    >
                                        Contacto y WhatsApp
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <div
                        class="
                            d-flex
                            align-items-center
                            gap-3
                            text-white
                        "
                    >
                        <div
                            class="
                                d-none
                                d-lg-flex
                                flex-column
                                text-end
                                small
                            "
                        >
                            <strong>
                                {{ auth()->user()->name }}
                            </strong>

                            <span class="opacity-75">
                                Administrador
                            </span>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('admin.logout') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="
                                    btn
                                    btn-sm
                                    btn-outline-light
                                "
                            >
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
    @endauth

    <main>
        @yield('content')
    </main>
</body>
</html>