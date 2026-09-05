@extends('admin.layout')

@section('title', 'Variantes')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Catálogo
                </span>

                <h1>Variantes</h1>

                <p>
                    {{ $product->name_es }}
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a
                    href="{{ route('admin.products.edit', $product) }}"
                    class="nv-button nv-button-outline"
                >
                    Editar producto
                </a>

                <a
                    href="{{ route('admin.products.index') }}"
                    class="nv-button nv-button-outline"
                >
                    Productos
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>
                    Hay campos que debes revisar.
                </strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="nv-admin-form-card mb-4">
            <div class="nv-admin-form-card-header">
                <div>
                    <h2>Variantes existentes</h2>

                    <p>
                        {{ $product->variants->count() }}
                        variante(s).
                    </p>
                </div>
            </div>

            @if ($product->variants->isEmpty())
                <div class="nv-admin-empty">
                    Este producto todavía no tiene variantes.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Variante</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th>Predeterminada</th>
                                <th>Orden</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($product->variants as $variant)
                                <tr>
                                    <td>
                                        <strong>
                                            {{ $variant->sku }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $variant->name_es }}
                                    </td>

                                    <td>
                                        @if ($variant->price !== null)
                                            {{ number_format(
                                                (float) $variant->price,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                            {{ $variant->currency }}
                                        @else
                                            Pendiente
                                        @endif
                                    </td>

                                    <td>
                                        {{ $variant->stock_quantity ?? '—' }}
                                    </td>

                                    <td>
                                        @if ($variant->is_active)
                                            <span class="nv-admin-status nv-admin-status-published">
                                                Activa
                                            </span>
                                        @else
                                            <span class="nv-admin-status nv-admin-status-archived">
                                                Inactiva
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $variant->is_default ? 'Sí' : 'No' }}
                                    </td>

                                    <td>
                                        {{ $variant->sort_order }}
                                    </td>

                                    <td class="text-end">
                                        <a
                                            href="{{ route(
                                                'admin.products.variants.edit',
                                                [
                                                    $product,
                                                    $variant,
                                                ]
                                            ) }}"
                                            class="nv-admin-action-link"
                                        >
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section class="nv-admin-form-card">
            <div class="nv-admin-form-card-header">
                <div>
                    <h2>Agregar variante</h2>

                    <p>
                        Crea una nueva presentación para
                        {{ $product->name_es }}.
                    </p>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route(
                    'admin.products.variants.store',
                    $product
                ) }}"
            >
                @csrf

                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label for="sku">
                            SKU *
                        </label>

                        <input
                            type="text"
                            id="sku"
                            name="sku"
                            class="form-control"
                            value="{{ old('sku') }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label for="name_es">
                            Nombre ES *
                        </label>

                        <input
                            type="text"
                            id="name_es"
                            name="name_es"
                            class="form-control"
                            value="{{ old('name_es') }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label for="name_en">
                            Nombre EN
                        </label>

                        <input
                            type="text"
                            id="name_en"
                            name="name_en"
                            class="form-control"
                            value="{{ old('name_en') }}"
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label for="price">
                            Precio
                        </label>

                        <input
                            type="number"
                            id="price"
                            name="price"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="{{ old('price') }}"
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label for="currency">
                            Moneda
                        </label>

                        <input
                            type="text"
                            id="currency"
                            name="currency"
                            class="form-control"
                            maxlength="3"
                            value="{{ old('currency', 'COP') }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label for="stock_status">
                            Estado stock
                        </label>

                        <select
                            id="stock_status"
                            name="stock_status"
                            class="form-select"
                            required
                        >
                            <option value="unknown">
                                Pendiente / desconocido
                            </option>

                            <option value="in_stock">
                                Disponible
                            </option>

                            <option value="out_of_stock">
                                Agotado
                            </option>

                            <option value="backorder">
                                Bajo pedido
                            </option>
                        </select>
                    </div>

                    <div class="nv-admin-field">
                        <label for="stock_quantity">
                            Cantidad
                        </label>

                        <input
                            type="number"
                            id="stock_quantity"
                            name="stock_quantity"
                            class="form-control"
                            min="0"
                            value="{{ old('stock_quantity') }}"
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label for="sort_order">
                            Orden
                        </label>

                        <input
                            type="number"
                            id="sort_order"
                            name="sort_order"
                            class="form-control"
                            min="0"
                            max="9999"
                            value="{{ old('sort_order') }}"
                            placeholder="Automático"
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
                                @checked(old('manage_stock'))
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
                                @checked(old('is_default'))
                            >

                            <span>
                                Variante predeterminada
                            </span>
                        </label>
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
                                @checked(old('is_active', true))
                            >

                            <span>Variante activa</span>
                        </label>
                    </div>
                </div>

                <div class="nv-admin-specifications">
                    <div class="nv-admin-specifications-header">
                        <div>
                            <h3>Especificaciones técnicas</h3>

                            <p>
                                Agrega cualquier atributo técnico.
                            </p>
                        </div>

                        <button
                            type="button"
                            class="nv-admin-action-link"
                            data-variant-spec-add="#new-variant-specifications"
                        >
                            + Agregar atributo
                        </button>
                    </div>

                    <div
                        id="new-variant-specifications"
                        class="nv-admin-spec-list"
                        data-variant-spec-list
                    >
                    </div>
                </div>

                <div class="nv-admin-form-actions px-4">
                    <button
                        type="submit"
                        class="nv-button nv-button-primary"
                    >
                        Crear variante
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection