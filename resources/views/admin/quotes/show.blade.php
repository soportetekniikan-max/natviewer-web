@extends('admin.layout')

@section('title', 'Cotización '.$quote->reference)

@section('content')
    @php
        $statusClass = match ($quote->status) {
            'new' => 'text-bg-warning',
            'contacted' => 'text-bg-info',
            'won' => 'text-bg-success',
            'lost' => 'text-bg-danger',
            'cancelled' => 'text-bg-secondary',
            default => 'text-bg-light',
        };

        $utmData = is_array($quote->utm_data)
            ? $quote->utm_data
            : [];

        $phoneHref = $quote->customer_phone
            ? 'tel:'.preg_replace(
                '/[^0-9+]/',
                '',
                $quote->customer_phone
            )
            : null;

        $emailHref = $quote->customer_email
            ? 'mailto:'.$quote->customer_email
            : null;
    @endphp

    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Cotización
                </span>

                <h1>
                    {{ $quote->reference }}
                </h1>

                <p>
                    Recibida el
                    {{ $quote->created_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <a
                href="{{ route('admin.quotes.index') }}"
                class="nv-button nv-button-outline"
            >
                Volver a cotizaciones
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

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

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <section class="nv-admin-form-card mb-4">
                    <div class="nv-admin-form-card-header">
                        <div>
                            <h2>Cliente</h2>

                            <p>
                                Información de contacto
                                proporcionada en la solicitud.
                            </p>
                        </div>
                    </div>

                    <div class="nv-admin-form-grid">
                        <div class="nv-admin-field">
                            <label>Nombre</label>

                            <strong>
                                {{ $quote->customer_name }}
                            </strong>
                        </div>

                        <div class="nv-admin-field">
                            <label>Teléfono</label>

                            @if ($phoneHref)
                                <a
                                    href="{{ $phoneHref }}"
                                    class="nv-admin-action-link"
                                >
                                    {{ $quote->customer_phone }}
                                </a>
                            @else
                                <span>—</span>
                            @endif
                        </div>

                        <div class="nv-admin-field">
                            <label>Email</label>

                            @if ($emailHref)
                                <a
                                    href="{{ $emailHref }}"
                                    class="nv-admin-action-link"
                                >
                                    {{ $quote->customer_email }}
                                </a>
                            @else
                                <span>—</span>
                            @endif
                        </div>

                        <div class="nv-admin-field">
                            <label>Idioma</label>

                            <span>
                                {{ strtoupper($quote->locale) }}
                            </span>
                        </div>

                        <div class="nv-admin-field nv-admin-field-full">
                            <label>Mensaje del cliente</label>

                            @if ($quote->customer_message)
                                <p class="mb-0">
                                    {{ $quote->customer_message }}
                                </p>
                            @else
                                <span>—</span>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="nv-admin-form-card mb-4">
                    <div class="nv-admin-form-card-header">
                        <div>
                            <h2>Producto solicitado</h2>
                        </div>
                    </div>

                    <div class="nv-admin-form-grid">
                        <div class="nv-admin-field">
                            <label>Producto</label>

                            <strong>
                                {{ $quote->product_name_snapshot ?? '—' }}
                            </strong>
                        </div>

                        <div class="nv-admin-field">
                            <label>Variante</label>

                            <strong>
                                {{ $quote->variant_name_snapshot ?? '—' }}
                            </strong>
                        </div>

                        <div class="nv-admin-field">
                            <label>Cantidad</label>

                            <span>
                                {{ $quote->quantity ?? 1 }}
                            </span>
                        </div>

                        <div class="nv-admin-field">
                            <label>Precio registrado</label>

                            @if ($quote->price_snapshot !== null)
                                <span>
                                    {{ number_format(
                                        (float) $quote->price_snapshot,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                    {{ $quote->currency }}
                                </span>
                            @else
                                <span>
                                    Precio pendiente
                                </span>
                            @endif
                        </div>
                    </div>
                </section>

                <section class="nv-admin-form-card mb-4">
                    <div class="nv-admin-form-card-header">
                        <div>
                            <h2>Origen de la solicitud</h2>

                            <p>
                                Información útil para analizar
                                adquisición y campañas.
                            </p>
                        </div>
                    </div>

                    <div class="nv-admin-form-grid">
                        <div class="nv-admin-field nv-admin-field-full">
                            <label>URL origen</label>

                            @if ($quote->source_url)
                                <a
                                    href="{{ $quote->source_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="nv-admin-action-link"
                                >
                                    {{ $quote->source_url }}
                                </a>
                            @else
                                <span>—</span>
                            @endif
                        </div>

                        <div class="nv-admin-field">
                            <label>WhatsApp abierto</label>

                            <span>
                                {{ $quote->whatsapp_opened_at
                                    ? $quote->whatsapp_opened_at->format(
                                        'd/m/Y H:i'
                                    )
                                    : 'No' }}
                            </span>
                        </div>
                    </div>

                    @if (! empty($utmData))
                        <div class="px-4 pb-4">
                            <h3 class="h6">
                                Datos UTM
                            </h3>

                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tbody>
                                        @foreach ($utmData as $key => $value)
                                            <tr>
                                                <th>
                                                    {{ $key }}
                                                </th>

                                                <td>
                                                    {{ is_scalar($value)
                                                        ? $value
                                                        : json_encode($value) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </section>
            </div>

            <div class="col-12 col-xl-4">
                <section class="nv-admin-form-card">
                    <div class="nv-admin-form-card-header">
                        <div>
                            <h2>Gestión comercial</h2>

                            <p>
                                Actualiza el seguimiento
                                interno de esta solicitud.
                            </p>
                        </div>

                        <span
                            class="badge rounded-pill {{ $statusClass }}"
                        >
                            {{ $statusLabels[$quote->status]
                                ?? $quote->status }}
                        </span>
                    </div>

                    <form
                        method="POST"
                        action="{{ route(
                            'admin.quotes.update',
                            $quote
                        ) }}"
                    >
                        @csrf
                        @method('PUT')

                        <div class="nv-admin-form-grid">
                            <div class="nv-admin-field nv-admin-field-full">
                                <label for="status">
                                    Estado
                                </label>

                                <select
                                    id="status"
                                    name="status"
                                    class="form-select"
                                    required
                                >
                                    @foreach ($statusLabels as $value => $label)
                                        <option
                                            value="{{ $value }}"
                                            @selected(
                                                old(
                                                    'status',
                                                    $quote->status
                                                ) === $value
                                            )
                                        >
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="nv-admin-field nv-admin-field-full">
                                <label for="admin_notes">
                                    Notas internas
                                </label>

                                <textarea
                                    id="admin_notes"
                                    name="admin_notes"
                                    class="form-control"
                                    rows="10"
                                    maxlength="5000"
                                    placeholder="Seguimiento, acuerdos, próximos pasos..."
                                >{{ old(
                                    'admin_notes',
                                    $quote->admin_notes
                                ) }}</textarea>

                                <small>
                                    Estas notas solo son visibles
                                    dentro del administrador.
                                </small>
                            </div>
                        </div>

                        <div class="nv-admin-form-actions px-4">
                            <button
                                type="submit"
                                class="nv-button nv-button-primary"
                            >
                                Guardar seguimiento
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
@endsection