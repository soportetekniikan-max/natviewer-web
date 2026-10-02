@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header">
            <div>
                <span class="nv-eyebrow">
                    Natviewer
                </span>

                <h1>Dashboard</h1>

                <p>
                    Resumen general del catálogo y las
                    solicitudes de cotización.
                </p>
            </div>
        </div>

        <div class="nv-admin-stats">
            <article>
                <span>Productos</span>

                <strong>
                    {{ $stats['products'] }}
                </strong>
            </article>

            <article>
                <span>Variantes activas</span>

                <strong>
                    {{ $stats['variants'] }}
                </strong>
            </article>

            <article>
                <span>Cotizaciones nuevas</span>

                <strong>
                    {{ $stats['new_quotes'] }}
                </strong>
            </article>

            <article>
                <span>Total cotizaciones</span>

                <strong>
                    {{ $stats['quotes_total'] }}
                </strong>
            </article>
        </div>

        {{-- MÓDULOS --}}
        <section class="nv-admin-panel mb-4">
            <div class="nv-admin-panel-header">
                <div>
                    <h2>Módulos de administración</h2>

                    <p>
                        Accesos principales para gestionar
                        Natviewer.
                    </p>
                </div>
            </div>

            <div class="row g-4 p-4 pt-0">
                <div class="col-12 col-md-6 col-xl-3">
                    <a
                        href="{{ route('admin.products.index') }}"
                        class="text-decoration-none text-reset d-block h-100"
                    >
                        <article
                            class="
                                bg-white
                                border
                                rounded-4
                                p-4
                                h-100
                                shadow-sm
                            "
                        >
                            <span
                                class="
                                    badge
                                    rounded-pill
                                    text-bg-dark
                                    mb-3
                                "
                            >
                                Catálogo
                            </span>

                            <h3 class="h5">
                                Productos
                            </h3>

                            <p class="text-secondary">
                                Productos, variantes,
                                precios, stock e imágenes.
                            </p>

                            <strong>
                                Administrar →
                            </strong>
                        </article>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="text-decoration-none text-reset d-block h-100"
                    >
                        <article
                            class="
                                bg-white
                                border
                                rounded-4
                                p-4
                                h-100
                                shadow-sm
                            "
                        >
                            <span
                                class="
                                    badge
                                    rounded-pill
                                    text-bg-success
                                    mb-3
                                "
                            >
                                Organización
                            </span>

                            <h3 class="h5">
                                Categorías
                            </h3>

                            <p class="text-secondary">
                                Clasificación y organización
                                del catálogo.
                            </p>

                            <strong>
                                Administrar →
                            </strong>
                        </article>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <a
                        href="{{ route('admin.brands.index') }}"
                        class="text-decoration-none text-reset d-block h-100"
                    >
                        <article
                            class="
                                bg-white
                                border
                                rounded-4
                                p-4
                                h-100
                                shadow-sm
                            "
                        >
                            <span
                                class="
                                    badge
                                    rounded-pill
                                    text-bg-warning
                                    mb-3
                                "
                            >
                                Catálogo
                            </span>

                            <h3 class="h5">
                                Marcas
                            </h3>

                            <p class="text-secondary">
                                Marcas, logos y
                                disponibilidad.
                            </p>

                            <strong>
                                Administrar →
                            </strong>
                        </article>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <a
                        href="{{ route('admin.quotes.index') }}"
                        class="text-decoration-none text-reset d-block h-100"
                    >
                        <article
                            class="
                                bg-white
                                border
                                rounded-4
                                p-4
                                h-100
                                shadow-sm
                            "
                        >
                            <span
                                class="
                                    badge
                                    rounded-pill
                                    text-bg-info
                                    mb-3
                                "
                            >
                                Comercial
                            </span>

                            <h3 class="h5">
                                Cotizaciones
                            </h3>

                            <p class="text-secondary">
                                Leads, seguimiento,
                                estados y notas internas.
                            </p>

                            <strong>
                                Gestionar →
                            </strong>
                        </article>
                    </a>
                </div>
            </div>
        </section>

        {{-- ÚLTIMAS COTIZACIONES --}}
        <section class="nv-admin-panel">
            <div class="nv-admin-panel-header">
                <div>
                    <h2>Últimas cotizaciones</h2>

                    <p>
                        Solicitudes comerciales recibidas
                        recientemente.
                    </p>
                </div>

                <a
                    href="{{ route('admin.quotes.index') }}"
                    class="nv-admin-action-link"
                >
                    Ver todas →
                </a>
            </div>

            @if ($latestQuotes->isEmpty())
                <div class="nv-admin-empty">
                    Todavía no hay solicitudes de cotización.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Referencia</th>
                                <th>Cliente</th>
                                <th>Producto</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($latestQuotes as $quote)
                                <tr>
                                    <td>
                                        <a
                                            href="{{ route(
                                                'admin.quotes.show',
                                                $quote
                                            ) }}"
                                            class="nv-admin-action-link"
                                        >
                                            {{ $quote->reference }}
                                        </a>
                                    </td>

                                    <td>
                                        {{ $quote->customer_name }}
                                    </td>

                                    <td>
                                        {{ $quote->variant_name_snapshot }}
                                    </td>

                                    <td>
                                        <span class="nv-admin-status">
                                            {{ $quote->status }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $quote->created_at
                                            ->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="text-end">
                                        <a
                                            href="{{ route(
                                                'admin.quotes.show',
                                                $quote
                                            ) }}"
                                            class="nv-admin-action-link"
                                        >
                                            Ver
                                        </a>
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