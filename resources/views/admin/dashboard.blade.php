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
                    Resumen general del catálogo y las solicitudes de cotización.
                </p>
            </div>
        </div>

        {{-- ESTADÍSTICAS --}}
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

        {{-- MÓDULOS DEL ADMIN --}}
        <section class="nv-admin-panel mb-4">
            <div class="nv-admin-panel-header">
                <div>
                    <h2>Gestión del catálogo</h2>

                    <p>
                        Accesos principales para administrar
                        el contenido comercial de Natviewer.
                    </p>
                </div>
            </div>

            <div class="row g-4 p-4 pt-0">
                {{-- PRODUCTOS --}}
                <div class="col-12 col-md-6 col-xl-4">
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
                            <div class="mb-3">
                                <span
                                    class="
                                        badge
                                        rounded-pill
                                        text-bg-dark
                                    "
                                >
                                    Catálogo
                                </span>
                            </div>

                            <h3 class="h5">
                                Productos
                            </h3>

                            <p class="text-secondary mb-4">
                                Administra productos, variantes,
                                precios, stock, imágenes y
                                especificaciones técnicas.
                            </p>

                            <strong>
                                Administrar productos →
                            </strong>
                        </article>
                    </a>
                </div>

                {{-- CATEGORÍAS --}}
                <div class="col-12 col-md-6 col-xl-4">
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
                            <div class="mb-3">
                                <span
                                    class="
                                        badge
                                        rounded-pill
                                        text-bg-success
                                    "
                                >
                                    Organización
                                </span>
                            </div>

                            <h3 class="h5">
                                Categorías
                            </h3>

                            <p class="text-secondary mb-4">
                                Crea y organiza las categorías
                                utilizadas para clasificar
                                los productos.
                            </p>

                            <strong>
                                Administrar categorías →
                            </strong>
                        </article>
                    </a>
                </div>

                {{-- MARCAS --}}
                <div class="col-12 col-md-6 col-xl-4">
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
                            <div class="mb-3">
                                <span
                                    class="
                                        badge
                                        rounded-pill
                                        text-bg-warning
                                    "
                                >
                                    Catálogo
                                </span>
                            </div>

                            <h3 class="h5">
                                Marcas
                            </h3>

                            <p class="text-secondary mb-4">
                                Administra fabricantes,
                                descripciones, logos,
                                orden y disponibilidad.
                            </p>

                            <strong>
                                Administrar marcas →
                            </strong>
                        </article>
                    </a>
                </div>
            </div>
        </section>

        {{-- COTIZACIONES --}}
        <section class="nv-admin-panel">
            <div class="nv-admin-panel-header">
                <div>
                    <h2>Últimas cotizaciones</h2>

                    <p>
                        Solicitudes comerciales recibidas recientemente.
                    </p>
                </div>
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
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($latestQuotes as $quote)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $quote->reference }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $quote->customer_name }}
                                    </td>

                                    <td>
                                        {{ $quote->variant_name_snapshot }}
                                    </td>

                                    <td>
                                        <span
                                            class="
                                                nv-admin-status
                                                nv-admin-status-{{ $quote->status }}
                                            "
                                        >
                                            {{ $quote->status }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $quote->created_at->format('d/m/Y H:i') }}
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