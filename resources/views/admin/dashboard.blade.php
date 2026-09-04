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

        <div class="nv-admin-stats">
            <article>
                <span>Productos</span>
                <strong>{{ $stats['products'] }}</strong>
            </article>

            <article>
                <span>Variantes activas</span>
                <strong>{{ $stats['variants'] }}</strong>
            </article>

            <article>
                <span>Cotizaciones nuevas</span>
                <strong>{{ $stats['new_quotes'] }}</strong>
            </article>

            <article>
                <span>Total cotizaciones</span>
                <strong>{{ $stats['quotes_total'] }}</strong>
            </article>
        </div>

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
                                        <span class="nv-admin-status">
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