<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <meta
        name="robots"
        content="noindex,nofollow"
    >

    <title>
        @yield('title', 'Administración') | Natviewer
    </title>

    @vite([
        'resources/css/admin.css',
        'resources/js/admin.js',
    ])

    @stack('head')
</head>

<body class="nv-admin-body">
    @auth
        @php
            $adminUser = auth()->user();

            $canCatalog = $adminUser->canAccessAdminArea(
                'catalog'
            );

            $canCommercial = $adminUser->canAccessAdminArea(
                'commercial'
            );

            $canUsers = $adminUser->canAccessAdminArea(
                'users'
            );
        @endphp

        <div class="nv-admin-shell">
            <aside
                class="
                    offcanvas-lg
                    offcanvas-start
                    nv-admin-sidebar
                "
                tabindex="-1"
                id="adminSidebar"
                aria-labelledby="adminSidebarLabel"
            >
                <div
                    class="
                        offcanvas-header
                        nv-admin-sidebar-mobile-header
                    "
                >
                    <span
                        id="adminSidebarLabel"
                        class="nv-admin-sidebar-mobile-title"
                    >
                        Administración
                    </span>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="offcanvas"
                        data-bs-target="#adminSidebar"
                        aria-label="Cerrar navegación"
                    ></button>
                </div>

                <div
                    class="
                        offcanvas-body
                        nv-admin-sidebar-body
                    "
                >
                    <div class="nv-admin-sidebar-inner">
                        <div class="nv-admin-sidebar-brand-wrap">
                            <a
                                href="{{ route(
                                    'admin.dashboard'
                                ) }}"
                                class="nv-admin-sidebar-brand"
                                aria-label="Natviewer Administración"
                            >
                                <img
                                    src="{{ asset(
                                        'images/logo-natviewer-white.png'
                                    ) }}"
                                    alt="Natviewer"
                                    class="nv-admin-sidebar-logo"
                                >
                            </a>

                            <span class="nv-admin-sidebar-product">
                                Administración
                            </span>
                        </div>

                        <nav
                            class="nv-admin-sidebar-nav"
                            aria-label="Navegación administrativa"
                        >
                            <div class="nv-admin-nav-section">
                                <span class="nv-admin-nav-section-label">
                                    Principal
                                </span>

                                <a
                                    href="{{ route(
                                        'admin.dashboard'
                                    ) }}"
                                    class="
                                        nv-admin-sidebar-link
                                        {{ request()->routeIs(
                                            'admin.dashboard'
                                        )
                                            ? 'is-active'
                                            : '' }}
                                    "
                                    @if (
                                        request()->routeIs(
                                            'admin.dashboard'
                                        )
                                    )
                                        aria-current="page"
                                    @endif
                                >
                                    <span
                                        class="nv-admin-sidebar-icon"
                                        aria-hidden="true"
                                    >
                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M4 4h6v6H4V4Z"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linejoin="round"
                                            />

                                            <path
                                                d="M14 4h6v6h-6V4Z"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linejoin="round"
                                            />

                                            <path
                                                d="M4 14h6v6H4v-6Z"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linejoin="round"
                                            />

                                            <path
                                                d="M14 14h6v6h-6v-6Z"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </span>

                                    <span>
                                        Dashboard
                                    </span>
                                </a>
                            </div>

                            @if ($canCatalog)
                                <div class="nv-admin-nav-section">
                                    <span class="nv-admin-nav-section-label">
                                        Catálogo
                                    </span>

                                    <a
                                        href="{{ route(
                                            'admin.products.index'
                                        ) }}"
                                        class="
                                            nv-admin-sidebar-link
                                            {{ request()->routeIs(
                                                'admin.products.*'
                                            )
                                                ? 'is-active'
                                                : '' }}
                                        "
                                        @if (
                                            request()->routeIs(
                                                'admin.products.*'
                                            )
                                        )
                                            aria-current="page"
                                        @endif
                                    >
                                        <span
                                            class="nv-admin-sidebar-icon"
                                            aria-hidden="true"
                                        >
                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <path
                                                    d="M5 7.5 12 4l7 3.5v9L12 20l-7-3.5v-9Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linejoin="round"
                                                />

                                                <path
                                                    d="m5 7.5 7 3.5 7-3.5"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linejoin="round"
                                                />

                                                <path
                                                    d="M12 11v9"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                />
                                            </svg>
                                        </span>

                                        <span>
                                            Productos
                                        </span>
                                    </a>

                                    <a
                                        href="{{ route(
                                            'admin.categories.index'
                                        ) }}"
                                        class="
                                            nv-admin-sidebar-link
                                            {{ request()->routeIs(
                                                'admin.categories.*'
                                            )
                                                ? 'is-active'
                                                : '' }}
                                        "
                                        @if (
                                            request()->routeIs(
                                                'admin.categories.*'
                                            )
                                        )
                                            aria-current="page"
                                        @endif
                                    >
                                        <span
                                            class="nv-admin-sidebar-icon"
                                            aria-hidden="true"
                                        >
                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <path
                                                    d="M4 6.5h6"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                />

                                                <path
                                                    d="M14 6.5h6"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                />

                                                <path
                                                    d="M4 12h10"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                />

                                                <path
                                                    d="M18 12h2"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                />

                                                <path
                                                    d="M4 17.5h3"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                />

                                                <path
                                                    d="M11 17.5h9"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                />

                                                <circle
                                                    cx="12"
                                                    cy="6.5"
                                                    r="2"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                />

                                                <circle
                                                    cx="16"
                                                    cy="12"
                                                    r="2"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                />

                                                <circle
                                                    cx="9"
                                                    cy="17.5"
                                                    r="2"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                />
                                            </svg>
                                        </span>

                                        <span>
                                            Categorías
                                        </span>
                                    </a>

                                    <a
                                        href="{{ route(
                                            'admin.brands.index'
                                        ) }}"
                                        class="
                                            nv-admin-sidebar-link
                                            {{ request()->routeIs(
                                                'admin.brands.*'
                                            )
                                                ? 'is-active'
                                                : '' }}
                                        "
                                        @if (
                                            request()->routeIs(
                                                'admin.brands.*'
                                            )
                                        )
                                            aria-current="page"
                                        @endif
                                    >
                                        <span
                                            class="nv-admin-sidebar-icon"
                                            aria-hidden="true"
                                        >
                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <path
                                                    d="M4 12V5.5A1.5 1.5 0 0 1 5.5 4H12l8 8-8 8-8-8Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linejoin="round"
                                                />

                                                <circle
                                                    cx="8"
                                                    cy="8"
                                                    r="1.25"
                                                    fill="currentColor"
                                                />
                                            </svg>
                                        </span>

                                        <span>
                                            Marcas
                                        </span>
                                    </a>
                                </div>
                            @endif

                            @if ($canCommercial)
                                <div class="nv-admin-nav-section">
                                    <span class="nv-admin-nav-section-label">
                                        Comercial
                                    </span>

                                    <a
                                        href="{{ route(
                                            'admin.quotes.index'
                                        ) }}"
                                        class="
                                            nv-admin-sidebar-link
                                            {{ request()->routeIs(
                                                'admin.quotes.*'
                                            )
                                                ? 'is-active'
                                                : '' }}
                                        "
                                        @if (
                                            request()->routeIs(
                                                'admin.quotes.*'
                                            )
                                        )
                                            aria-current="page"
                                        @endif
                                    >
                                        <span
                                            class="nv-admin-sidebar-icon"
                                            aria-hidden="true"
                                        >
                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <path
                                                    d="M5 5h14v11H9l-4 3V5Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linejoin="round"
                                                />

                                                <path
                                                    d="M8 9h8"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                />

                                                <path
                                                    d="M8 12.5h5"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                />
                                            </svg>
                                        </span>

                                        <span>
                                            Cotizaciones
                                        </span>
                                    </a>
                                </div>

                                <div class="nv-admin-nav-section">
                                    <span class="nv-admin-nav-section-label">
                                        Sistema
                                    </span>

                                    <a
                                        href="{{ route(
                                            'admin.contact-settings.edit'
                                        ) }}"
                                        class="
                                            nv-admin-sidebar-link
                                            {{ request()->routeIs(
                                                'admin.contact-settings.*'
                                            )
                                                ? 'is-active'
                                                : '' }}
                                        "
                                        @if (
                                            request()->routeIs(
                                                'admin.contact-settings.*'
                                            )
                                        )
                                            aria-current="page"
                                        @endif
                                    >
                                        <span
                                            class="nv-admin-sidebar-icon"
                                            aria-hidden="true"
                                        >
                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <path
                                                    d="M12 8.5a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                />

                                                <path
                                                    d="M19 13.2a7.4 7.4 0 0 0 0-2.4l2-1.5-2-3.4-2.5 1a7.6 7.6 0 0 0-2.1-1.2L14 3h-4l-.4 2.7a7.6 7.6 0 0 0-2.1 1.2l-2.5-1-2 3.4 2 1.5a7.4 7.4 0 0 0 0 2.4l-2 1.5 2 3.4 2.5-1a7.6 7.6 0 0 0 2.1 1.2L10 21h4l.4-2.7a7.6 7.6 0 0 0 2.1-1.2l2.5 1 2-3.4-2-1.5Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                        </span>

                                        <span>
                                            Configuración
                                        </span>
                                    </a>
                                </div>
                            @endif

                            @if ($canUsers)
                                <div
                                    class="
                                        nv-admin-nav-section
                                        nv-admin-nav-section-users
                                    "
                                >
                                    @unless ($canCommercial)
                                        <span class="nv-admin-nav-section-label">
                                            Sistema
                                        </span>
                                    @endunless

                                    <a
                                        href="{{ route(
                                            'admin.users.index'
                                        ) }}"
                                        class="
                                            nv-admin-sidebar-link
                                            {{ request()->routeIs(
                                                'admin.users.*'
                                            )
                                                ? 'is-active'
                                                : '' }}
                                        "
                                        @if (
                                            request()->routeIs(
                                                'admin.users.*'
                                            )
                                        )
                                            aria-current="page"
                                        @endif
                                    >
                                        <span
                                            class="nv-admin-sidebar-icon"
                                            aria-hidden="true"
                                        >
                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <circle
                                                    cx="12"
                                                    cy="8"
                                                    r="3.25"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                />

                                                <path
                                                    d="M5.5 19c.65-3.3 3-5 6.5-5s5.85 1.7 6.5 5"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                />
                                            </svg>
                                        </span>

                                        <span>
                                            Usuarios
                                        </span>
                                    </a>
                                </div>
                            @endif
                        </nav>

                        <div class="nv-admin-sidebar-footer">
                            <div class="nv-admin-sidebar-user">
                                <div
                                    class="nv-admin-user-avatar"
                                    aria-hidden="true"
                                >
                                    {{ mb_strtoupper(
                                        mb_substr(
                                            $adminUser->name,
                                            0,
                                            1
                                        )
                                    ) }}
                                </div>

                                <div class="nv-admin-sidebar-user-copy">
                                    <strong>
                                        {{ $adminUser->name }}
                                    </strong>

                                    <span>
                                        {{ $adminUser
                                            ->adminRoleLabel() }}
                                    </span>
                                </div>
                            </div>

                            <form
                                method="POST"
                                action="{{ route(
                                    'admin.logout'
                                ) }}"
                                class="nv-admin-sidebar-logout-form"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="nv-admin-sidebar-logout"
                                >
                                    <span
                                        class="nv-admin-sidebar-icon"
                                        aria-hidden="true"
                                    >
                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M10 5H6.5A1.5 1.5 0 0 0 5 6.5v11A1.5 1.5 0 0 0 6.5 19H10"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                            />

                                            <path
                                                d="M14 8l4 4-4 4"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />

                                            <path
                                                d="M9 12h9"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                            />
                                        </svg>
                                    </span>

                                    <span>
                                        Cerrar sesión
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="nv-admin-workspace">
                <header class="nv-admin-topbar">
                    <div class="nv-admin-topbar-left">
                        <button
                            type="button"
                            class="nv-admin-menu-toggle d-lg-none"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#adminSidebar"
                            aria-controls="adminSidebar"
                            aria-label="Abrir navegación"
                        >
                            <span aria-hidden="true"></span>
                            <span aria-hidden="true"></span>
                            <span aria-hidden="true"></span>
                        </button>

                        <div class="nv-admin-topbar-context">
                            <span>
                                Natviewer Admin
                            </span>

                            <strong>
                                @yield('title', 'Administración')
                            </strong>
                        </div>
                    </div>

                    <div class="nv-admin-topbar-right">
                        <a
                            href="{{ url('/') }}"
                            class="nv-admin-view-site"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span>
                                Ver sitio
                            </span>

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                            >
                                <path
                                    d="M9 5h10v10"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                                <path
                                    d="m19 5-9 9"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                />

                                <path
                                    d="M15 12v6a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1v-8a1 1 0 0 1 1-1h6"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>

                        <div class="nv-admin-topbar-user">
                            <div
                                class="nv-admin-user-avatar"
                                aria-hidden="true"
                            >
                                {{ mb_strtoupper(
                                    mb_substr(
                                        $adminUser->name,
                                        0,
                                        1
                                    )
                                ) }}
                            </div>

                            <div class="nv-admin-topbar-user-copy">
                                <strong>
                                    {{ $adminUser->name }}
                                </strong>

                                <span>
                                    {{ $adminUser
                                        ->adminRoleLabel() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="nv-admin-main">
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <main class="nv-admin-main nv-admin-main-guest">
            @yield('content')
        </main>
    @endauth

    @stack('scripts')
</body>
</html>