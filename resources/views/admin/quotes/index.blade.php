@extends('admin.layout')

@section('title', 'Cotizaciones')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Comercial
                </span>

                <h1>Cotizaciones</h1>

                <p>
                    Gestiona las solicitudes comerciales
                    recibidas desde el sitio.
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- RESUMEN --}}
        <div class="nv-admin-stats mb-4">
            <article>
                <span>Total</span>

                <strong>
                    {{ $summary['total'] }}
                </strong>
            </article>

            <article>
                <span>Nuevas</span>

                <strong>
                    {{ $summary['new'] }}
                </strong>
            </article>

            <article>
                <span>Contactadas</span>

                <strong>
                    {{ $summary['contacted'] }}
                </strong>
            </article>

            <article>
                <span>Ganadas</span>

                <strong>
                    {{ $summary['won'] }}
                </strong>
            </article>

            <article>
                <span>Perdidas</span>

                <strong>
                    {{ $summary['lost'] }}
                </strong>
            </article>
        </div>

        {{-- FILTROS --}}
        <section class="nv-admin-form-card mb-4">
            <div class="nv-admin-form-card-header">
                <div>
                    <h2>Buscar y filtrar</h2>

                    <p>
                        Encuentra solicitudes por referencia,
                        cliente, teléfono o email.
                    </p>
                </div>
            </div>

            <form
                method="GET"
                action="{{ route('admin.quotes.index') }}"
            >
                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label for="q">
                            Buscar
                        </label>

                        <input
                            type="search"
                            id="q"
                            name="q"
                            class="form-control"
                            value="{{ $search }}"
                            placeholder="Referencia, cliente, teléfono..."
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label for="status">
                            Estado
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-select"
                        >
                            <option value="">
                                Todos los estados
                            </option>

                            @foreach ($statusLabels as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        $currentStatus === $value
                                    )
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="nv-admin-form-actions px-4">
                    <a
                        href="{{ route('admin.quotes.index') }}"
                        class="nv-button nv-button-outline"
                    >
                        Limpiar
                    </a>

                    <button
                        type="submit"
                        class="nv-button nv-button-primary"
                    >
                        Aplicar filtros
                    </button>
                </div>
            </form>
        </section>

        {{-- LISTADO --}}
        <section class="nv-admin-panel">
            <div class="nv-admin-panel-header">
                <div>
                    <h2>Solicitudes</h2>

                    <p>
                        {{ $quotes->total() }}
                        cotización(es) encontrada(s).
                    </p>
                </div>
            </div>

            @if ($quotes->isEmpty())
                <div class="nv-admin-empty">
                    No hay cotizaciones que coincidan
                    con los filtros actuales.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Referencia</th>
                                <th>Cliente</th>
                                <th>Contacto</th>
                                <th>Producto / variante</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($quotes as $quote)
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
                                        <strong>
                                            {{ $quote->customer_name }}
                                        </strong>

                                        @if ($quote->customer_email)
                                            <div>
                                                <small>
                                                    {{ $quote->customer_email }}
                                                </small>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $quote->customer_phone }}
                                    </td>

                                    <td>
                                        {{ $quote->variant_name_snapshot }}

                                        @if ($quote->quantity)
                                            <div>
                                                <small>
                                                    Cantidad:
                                                    {{ $quote->quantity }}
                                                </small>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $statusClass = match ($quote->status) {
                                                'new' => 'text-bg-warning',
                                                'contacted' => 'text-bg-info',
                                                'won' => 'text-bg-success',
                                                'lost' => 'text-bg-danger',
                                                'cancelled' => 'text-bg-secondary',
                                                default => 'text-bg-light',
                                            };
                                        @endphp

                                        <span
                                            class="badge rounded-pill {{ $statusClass }}"
                                        >
                                            {{ $statusLabels[$quote->status]
                                                ?? $quote->status }}
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
                                            Ver detalle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($quotes->hasPages())
                    <div class="nv-admin-pagination">
                        {{ $quotes->links() }}
                    </div>
                @endif
            @endif
        </section>
    </div>
@endsection