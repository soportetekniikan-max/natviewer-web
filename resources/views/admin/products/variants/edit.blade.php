@extends('admin.layout')

@section('title', 'Editar variante')

@section('content')
    @php
        $specifications = collect(
            $variant->specifications ?? []
        )
            ->map(
                fn ($value, $key) => [
                    'key' => $key,
                    'value' => $value,
                ]
            )
            ->values()
            ->all();
    @endphp

    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    {{ $product->name_es }}
                </span>

                <h1>Editar variante</h1>

                <p>
                    {{ $variant->name_es }}
                </p>
            </div>

            <a
                href="{{ route(
                    'admin.products.variants.index',
                    $product
                ) }}"
                class="nv-button nv-button-outline"
            >
                Volver a variantes
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
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route(
                'admin.products.variants.update',
                [
                    $product,
                    $variant,
                ]
            ) }}"
            class="nv-admin-product-form"
        >
            @csrf
            @method('PUT')

            <section class="nv-admin-form-card">
                <div class="nv-admin-form-card-header">
                    <div>
                        <h2>
                            Datos de la variante
                        </h2>

                        <p>
                            Precio, SKU, stock,
                            disponibilidad y estado.
                        </p>
                    </div>
                </div>

                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label>SKU *</label>

                        <input
                            type="text"
                            name="sku"
                            class="form-control"
                            value="{{ old('sku', $variant->sku) }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Nombre ES *</label>

                        <input
                            type="text"
                            name="name_es"
                            class="form-control"
                            value="{{ old('name_es', $variant->name_es) }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Nombre EN</label>

                        <input
                            type="text"
                            name="name_en"
                            class="form-control"
                            value="{{ old('name_en', $variant->name_en) }}"
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Precio</label>

                        <input
                            type="number"
                            name="price"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="{{ old('price', $variant->price) }}"
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Moneda</label>

                        <input
                            type="text"
                            name="currency"
                            class="form-control"
                            maxlength="3"
                            value="{{ old('currency', $variant->currency) }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Estado stock</label>

                        <select
                            name="stock_status"
                            class="form-select"
                            required
                        >
                            @foreach ([
                                'unknown' => 'Pendiente / desconocido',
                                'in_stock' => 'Disponible',
                                'out_of_stock' => 'Agotado',
                                'backorder' => 'Bajo pedido',
                            ] as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'stock_status',
                                            $variant->stock_status
                                        ) === $value
                                    )
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="nv-admin-field">
                        <label>Cantidad disponible</label>

                        <input
                            type="number"
                            name="stock_quantity"
                            class="form-control"
                            min="0"
                            value="{{ old(
                                'stock_quantity',
                                $variant->stock_quantity
                            ) }}"
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Orden</label>

                        <input
                            type="number"
                            name="sort_order"
                            class="form-control"
                            min="0"
                            max="9999"
                            value="{{ old(
                                'sort_order',
                                $variant->sort_order
                            ) }}"
                        >
                    </div>

                    <div class="nv-admin-field">
                        <input
                            type="hidden"
                            name="manage_stock"
                            value="0"
                        >

                        <label class="nv-admin-toggle">
                            <input
                                type="checkbox"
                                name="manage_stock"
                                value="1"
                                @checked(
                                    old(
                                        'manage_stock',
                                        $variant->manage_stock
                                    )
                                )
                            >

                            <span>Gestionar stock</span>
                        </label>
                    </div>

                    <div class="nv-admin-field">
                        <input
                            type="hidden"
                            name="is_default"
                            value="0"
                        >

                        <label class="nv-admin-toggle">
                            <input
                                type="checkbox"
                                name="is_default"
                                value="1"
                                @checked(
                                    old(
                                        'is_default',
                                        $variant->is_default
                                    )
                                )
                            >

                            <span>
                                Variante predeterminada
                            </span>
                        </label>

                        @if ($variant->is_default)
                            <small>
                                Para cambiar la predeterminada,
                                marca otra variante como predeterminada.
                            </small>
                        @endif
                    </div>

                    <div class="nv-admin-field">
                        <input
                            type="hidden"
                            name="is_active"
                            value="0"
                        >

                        <label class="nv-admin-toggle">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                @checked(
                                    old(
                                        'is_active',
                                        $variant->is_active
                                    )
                                )
                            >

                            <span>Variante activa</span>
                        </label>
                    </div>
                </div>

                <div class="nv-admin-specifications">
                    <div class="nv-admin-specifications-header">
                        <div>
                            <h3>
                                Especificaciones técnicas
                            </h3>
                        </div>

                        <button
                            type="button"
                            class="nv-admin-action-link"
                            data-variant-spec-add="#variant-specifications"
                        >
                            + Agregar atributo
                        </button>
                    </div>

                    <div
                        id="variant-specifications"
                        class="nv-admin-spec-list"
                        data-variant-spec-list
                    >
                        @foreach ($specifications as $index => $specification)
                            <div
                                class="nv-admin-spec-row"
                                data-variant-spec-row
                            >
                                <div class="nv-admin-field">
                                    <label>
                                        Especificación
                                    </label>

                                    <input
                                        type="text"
                                        name="specifications[{{ $index }}][key]"
                                        class="form-control"
                                        value="{{ $specification['key'] }}"
                                        maxlength="100"
                                        data-variant-spec-key
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <label>Valor</label>

                                    <input
                                        type="text"
                                        name="specifications[{{ $index }}][value]"
                                        class="form-control"
                                        value="{{ $specification['value'] }}"
                                        maxlength="500"
                                        data-variant-spec-value
                                    >
                                </div>

                                <div class="nv-admin-spec-remove-wrap">
                                    <button
                                        type="button"
                                        class="nv-admin-spec-remove"
                                        data-variant-spec-remove
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <div class="nv-admin-form-actions">
                <a
                    href="{{ route(
                        'admin.products.variants.index',
                        $product
                    ) }}"
                    class="nv-button nv-button-outline"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="nv-button nv-button-primary"
                >
                    Guardar variante
                </button>
            </div>
        </form>
    </div>
@endsection