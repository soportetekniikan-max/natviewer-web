@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        {{-- =====================================================
             HERO / PAGE INTRO
             ===================================================== --}}
        <section class="nv-dashboard-hero">
            <div class="nv-dashboard-hero-copy">
                <span class="nv-eyebrow">
                    Centro de control
                </span>

                <h1>
                    Dashboard
                </h1>

                <p>
                    Supervisa el catálogo, las solicitudes
                    comerciales y los accesos principales
                    de Natviewer desde un solo lugar.
                </p>
            </div>

            <div class="nv-dashboard-hero-user">
                <span class="nv-dashboard-hero-user-label">
                    Sesión activa
                </span>

                <strong>
                    {{ auth()->user()->name }}
                </strong>

                <span>
                    {{ auth()->user()->adminRoleLabel() }}
                </span>
            </div>
        </section>


        {{-- =====================================================
             STATISTICS
             ===================================================== --}}
        <section
            class="nv-dashboard-stats"
            aria-label="Resumen general"
        >
            <article
                class="
                    nv-dashboard-stat
                    nv-dashboard-stat-products
                "
            >
                <div class="nv-dashboard-stat-top">
                    <span class="nv-dashboard-stat-icon">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            aria-hidden="true"
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

                    <span class="nv-dashboard-stat-label">
                        Productos
                    </span>
                </div>

                <strong class="nv-dashboard-stat-value">
                    {{ $stats['products'] }}
                </strong>

                <p>
                    Registros disponibles en el catálogo
                    administrativo.
                </p>
            </article>

            <article
                class="
                    nv-dashboard-stat
                    nv-dashboard-stat-variants
                "
            >
                <div class="nv-dashboard-stat-top">
                    <span class="nv-dashboard-stat-icon">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M7 5h10"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />

                            <path
                                d="M5 9h14v10H5V9Z"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linejoin="round"
                            />

                            <path
                                d="M9 13h6"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />
                        </svg>
                    </span>

                    <span class="nv-dashboard-stat-label">
                        Variantes activas
                    </span>
                </div>

                <strong class="nv-dashboard-stat-value">
                    {{ $stats['variants'] }}
                </strong>

                <p>
                    Configuraciones activas actualmente
                    dentro del catálogo.
                </p>
            </article>

            <article
                class="
                    nv-dashboard-stat
                    nv-dashboard-stat-new-quotes
                "
            >
                <div class="nv-dashboard-stat-top">
                    <span class="nv-dashboard-stat-icon">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M5 5h14v11H9l-4 3V5Z"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linejoin="round"
                            />

                            <path
                                d="M12 8v5"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />

                            <path
                                d="M9.5 10.5h5"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />
                        </svg>
                    </span>

                    <span class="nv-dashboard-stat-label">
                        Cotizaciones nuevas
                    </span>
                </div>

                <strong class="nv-dashboard-stat-value">
                    {{ $stats['new_quotes'] }}
                </strong>

                <p>
                    Solicitudes pendientes de primera
                    revisión comercial.
                </p>
            </article>

            <article
                class="
                    nv-dashboard-stat
                    nv-dashboard-stat-total-quotes
                "
            >
                <div class="nv-dashboard-stat-top">
                    <span class="nv-dashboard-stat-icon">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M5 4h14v16H5V4Z"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linejoin="round"
                            />

                            <path
                                d="M8 8h8"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />

                            <path
                                d="M8 12h8"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />

                            <path
                                d="M8 16h5"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />
                        </svg>
                    </span>

                    <span class="nv-dashboard-stat-label">
                        Total cotizaciones
                    </span>
                </div>

                <strong class="nv-dashboard-stat-value">
                    {{ $stats['quotes_total'] }}
                </strong>

                <p>
                    Historial total de solicitudes
                    comerciales registradas.
                </p>
            </article>
        </section>


        {{-- =====================================================
             MAIN DASHBOARD GRID
             ===================================================== --}}
        <div class="nv-dashboard-main-grid">
            {{-- =================================================
                 RECENT QUOTES
                 ================================================= --}}
            <section class="nv-dashboard-card nv-dashboard-quotes">
                <div class="nv-dashboard-card-header">
                    <div>
                        <span class="nv-dashboard-section-kicker">
                            Actividad comercial
                        </span>

                        <h2>
                            Últimas cotizaciones
                        </h2>

                        <p>
                            Solicitudes recibidas recientemente.
                        </p>
                    </div>

                    @if (
                        auth()->user()->canAccessAdminArea(
                            'commercial'
                        )
                    )
                        <a
                            href="{{ route(
                                'admin.quotes.index'
                            ) }}"
                            class="nv-dashboard-header-action"
                        >
                            <span>
                                Ver todas
                            </span>

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                            >
                                <path
                                    d="m9 6 6 6-6 6"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </a>
                    @endif
                </div>

                @if ($latestQuotes->isEmpty())
                    <div class="nv-dashboard-empty">
                        <span class="nv-dashboard-empty-icon">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                            >
                                <path
                                    d="M5 5h14v11H9l-4 3V5Z"
                                    stroke="currentColor"
                                    stroke-width="1.6"
                                    stroke-linejoin="round"
                                />

                                <path
                                    d="M9 10h6"
                                    stroke="currentColor"
                                    stroke-width="1.6"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </span>

                        <strong>
                            Sin cotizaciones recientes
                        </strong>

                        <p>
                            Las nuevas solicitudes aparecerán
                            aquí cuando sean registradas.
                        </p>
                    </div>
                @else
                    <div class="nv-dashboard-quotes-list">
                        @foreach ($latestQuotes as $quote)
                            <article class="nv-dashboard-quote-item">
                                <div class="nv-dashboard-quote-main">
                                    <div class="nv-dashboard-quote-reference">
                                        <span>
                                            Referencia
                                        </span>

                                        <a
                                            href="{{ route(
                                                'admin.quotes.show',
                                                $quote
                                            ) }}"
                                        >
                                            {{ $quote->reference }}
                                        </a>
                                    </div>

                                    <div class="nv-dashboard-quote-customer">
                                        <strong>
                                            {{ $quote->customer_name }}
                                        </strong>

                                        <span>
                                            {{ $quote->variant_name_snapshot }}
                                        </span>
                                    </div>
                                </div>

                                <div class="nv-dashboard-quote-meta">
                                    <span
                                        class="nv-dashboard-status"
                                        data-status="{{ $quote->status }}"
                                    >
                                        {{ str($quote->status)
                                            ->replace('_', ' ')
                                            ->title() }}
                                    </span>

                                    <time
                                        datetime="{{ $quote->created_at
                                            ->toIso8601String() }}"
                                    >
                                        {{ $quote->created_at
                                            ->format('d/m/Y H:i') }}
                                    </time>

                                    <a
                                        href="{{ route(
                                            'admin.quotes.show',
                                            $quote
                                        ) }}"
                                        class="nv-dashboard-quote-open"
                                        aria-label="Ver cotización {{ $quote->reference }}"
                                    >
                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="m9 6 6 6-6 6"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>


            {{-- =================================================
                 QUICK ACCESS
                 ================================================= --}}
            <aside class="nv-dashboard-card nv-dashboard-quick-access">
                <div class="nv-dashboard-card-header">
                    <div>
                        <span class="nv-dashboard-section-kicker">
                            Navegación
                        </span>

                        <h2>
                            Accesos rápidos
                        </h2>

                        <p>
                            Herramientas principales del panel.
                        </p>
                    </div>
                </div>

                <div class="nv-dashboard-quick-list">
                    @if (
                        auth()->user()->canAccessAdminArea(
                            'catalog'
                        )
                    )
                        <a
                            href="{{ route(
                                'admin.products.index'
                            ) }}"
                            class="nv-dashboard-quick-link"
                        >
                            <span class="nv-dashboard-quick-icon">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true"
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
                                </svg>
                            </span>

                            <span class="nv-dashboard-quick-copy">
                                <strong>
                                    Productos
                                </strong>

                                <small>
                                    Gestionar catálogo
                                </small>
                            </span>

                            <span
                                class="nv-dashboard-quick-arrow"
                                aria-hidden="true"
                            >
                                →
                            </span>
                        </a>

                        <a
                            href="{{ route(
                                'admin.categories.index'
                            ) }}"
                            class="nv-dashboard-quick-link"
                        >
                            <span class="nv-dashboard-quick-icon">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M5 7h14"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="M5 12h14"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="M5 17h9"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                    />
                                </svg>
                            </span>

                            <span class="nv-dashboard-quick-copy">
                                <strong>
                                    Categorías
                                </strong>

                                <small>
                                    Organizar catálogo
                                </small>
                            </span>

                            <span
                                class="nv-dashboard-quick-arrow"
                                aria-hidden="true"
                            >
                                →
                            </span>
                        </a>

                        <a
                            href="{{ route(
                                'admin.brands.index'
                            ) }}"
                            class="nv-dashboard-quick-link"
                        >
                            <span class="nv-dashboard-quick-icon">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true"
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

                            <span class="nv-dashboard-quick-copy">
                                <strong>
                                    Marcas
                                </strong>

                                <small>
                                    Gestionar marcas
                                </small>
                            </span>

                            <span
                                class="nv-dashboard-quick-arrow"
                                aria-hidden="true"
                            >
                                →
                            </span>
                        </a>
                    @endif

                    @if (
                        auth()->user()->canAccessAdminArea(
                            'commercial'
                        )
                    )
                        <a
                            href="{{ route(
                                'admin.quotes.index'
                            ) }}"
                            class="
                                nv-dashboard-quick-link
                                nv-dashboard-quick-link-highlight
                            "
                        >
                            <span class="nv-dashboard-quick-icon">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true"
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

                            <span class="nv-dashboard-quick-copy">
                                <strong>
                                    Cotizaciones
                                </strong>

                                <small>
                                    Gestionar solicitudes
                                </small>
                            </span>

                            <span
                                class="nv-dashboard-quick-arrow"
                                aria-hidden="true"
                            >
                                →
                            </span>
                        </a>

                        <a
                            href="{{ route(
                                'admin.contact-settings.edit'
                            ) }}"
                            class="nv-dashboard-quick-link"
                        >
                            <span class="nv-dashboard-quick-icon">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true"
                                >
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="3.5"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    />

                                    <path
                                        d="M12 3v2"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="M12 19v2"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="m5.6 5.6 1.4 1.4"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="m17 17 1.4 1.4"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="M3 12h2"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="M19 12h2"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                    />
                                </svg>
                            </span>

                            <span class="nv-dashboard-quick-copy">
                                <strong>
                                    Configuración
                                </strong>

                                <small>
                                    Contacto y WhatsApp
                                </small>
                            </span>

                            <span
                                class="nv-dashboard-quick-arrow"
                                aria-hidden="true"
                            >
                                →
                            </span>
                        </a>
                    @endif

                    @if (
                        auth()->user()->canAccessAdminArea(
                            'users'
                        )
                    )
                        <a
                            href="{{ route(
                                'admin.users.index'
                            ) }}"
                            class="nv-dashboard-quick-link"
                        >
                            <span class="nv-dashboard-quick-icon">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true"
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

                            <span class="nv-dashboard-quick-copy">
                                <strong>
                                    Usuarios
                                </strong>

                                <small>
                                    Accesos administrativos
                                </small>
                            </span>

                            <span
                                class="nv-dashboard-quick-arrow"
                                aria-hidden="true"
                            >
                                →
                            </span>
                        </a>
                    @endif
                </div>
            </aside>
        </div>


        {{-- =====================================================
             OPERATIONS STRIP
             ===================================================== --}}
        <section class="nv-dashboard-operations">
            <div class="nv-dashboard-operations-copy">
                <span class="nv-dashboard-section-kicker">
                    Operación Natviewer
                </span>

                <h2>
                    Todo lo necesario para gestionar el sitio
                </h2>

                <p>
                    El panel mantiene catálogo, actividad
                    comercial y configuración separados para
                    facilitar el trabajo diario.
                </p>
            </div>

            <div class="nv-dashboard-operations-items">
                @if (
                    auth()->user()->canAccessAdminArea(
                        'catalog'
                    )
                )
                    <div class="nv-dashboard-operation">
                        <span>
                            01
                        </span>

                        <div>
                            <strong>
                                Catálogo
                            </strong>

                            <small>
                                Productos, variantes y contenido.
                            </small>
                        </div>
                    </div>
                @endif

                @if (
                    auth()->user()->canAccessAdminArea(
                        'commercial'
                    )
                )
                    <div class="nv-dashboard-operation">
                        <span>
                            02
                        </span>

                        <div>
                            <strong>
                                Comercial
                            </strong>

                            <small>
                                Solicitudes y seguimiento.
                            </small>
                        </div>
                    </div>

                    <div class="nv-dashboard-operation">
                        <span>
                            03
                        </span>

                        <div>
                            <strong>
                                Configuración
                            </strong>

                            <small>
                                Contacto, WhatsApp e idioma.
                            </small>
                        </div>
                    </div>
                @endif

                @if (
                    auth()->user()->canAccessAdminArea(
                        'users'
                    )
                )
                    <div class="nv-dashboard-operation">
                        <span>
                            04
                        </span>

                        <div>
                            <strong>
                                Seguridad
                            </strong>

                            <small>
                                Usuarios, roles y accesos.
                            </small>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection