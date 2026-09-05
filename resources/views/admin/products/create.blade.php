@extends('admin.layout')

@section('title', 'Nuevo producto')

@section('content')
    @php
        $oldVariants = old('variants', [
            0 => [
                'sku' => '',
                'name_es' => '',
                'name_en' => '',
                'price' => '',
                'currency' => 'COP',
                'manage_stock' => 0,
                'stock_quantity' => '',
                'stock_status' => 'unknown',
                'is_default' => 1,
                'is_active' => 1,
                'sort_order' => 10,
                'specifications' => [
                    [
                        'key' => '',
                        'value' => '',
                    ],
                ],
            ],
        ]);

        $variantKeys = array_keys($oldVariants);

        $nextVariantKey =
            empty($variantKeys)
                ? 0
                : max(
                    array_map(
                        'intval',
                        $variantKeys
                    )
                ) + 1;
    @endphp

    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Catálogo
                </span>

                <h1>Nuevo producto</h1>

                <p>
                    Crea el producto completo con variantes,
                    especificaciones técnicas e imágenes.
                </p>
            </div>

            <a
                href="{{ route('admin.products.index') }}"
                class="nv-button nv-button-outline"
            >
                Volver a productos
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>
                    Hay campos que debes revisar.
                </strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>

                <p class="mb-0 mt-3">
                    Si habías seleccionado imágenes,
                    vuelve a seleccionarlas por seguridad
                    del navegador.
                </p>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('admin.products.store') }}"
            enctype="multipart/form-data"
            class="nv-admin-product-form"
        >
            @csrf

            {{-- GENERAL --}}
            <section class="nv-admin-form-card">
                <div class="nv-admin-form-card-header">
                    <div>
                        <h2>
                            Información general
                        </h2>

                        <p>
                            El producto quedará inicialmente
                            como borrador.
                        </p>
                    </div>
                </div>

                <div class="nv-admin-form-grid">
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
                            autofocus
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

                    <div class="nv-admin-field nv-admin-field-full">
                        <label for="slug">
                            Slug
                        </label>

                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            class="form-control"
                            value="{{ old('slug') }}"
                            placeholder="natviewer-falco"
                        >

                        <small>
                            Opcional. Si lo dejas vacío,
                            se genera automáticamente.
                        </small>
                    </div>

                    <div class="nv-admin-field">
                        <label for="category_id">
                            Categoría *
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            class="form-select"
                            required
                        >
                            <option value="">
                                Selecciona una categoría
                            </option>

                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(
                                        old('category_id')
                                        == $category->id
                                    )
                                >
                                    {{ $category->name_es }}

                                    {{ ! $category->is_active
                                        ? ' (inactiva)'
                                        : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="nv-admin-field">
                        <label for="brand_id">
                            Marca *
                        </label>

                        <select
                            id="brand_id"
                            name="brand_id"
                            class="form-select"
                            required
                        >
                            <option value="">
                                Selecciona una marca
                            </option>

                            @foreach ($brands as $brand)
                                <option
                                    value="{{ $brand->id }}"
                                    @selected(
                                        old('brand_id')
                                        == $brand->id
                                    )
                                >
                                    {{ $brand->name }}

                                    {{ ! $brand->is_active
                                        ? ' (inactiva)'
                                        : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label for="short_description_es">
                            Descripción corta ES
                        </label>

                        <textarea
                            id="short_description_es"
                            name="short_description_es"
                            class="form-control"
                            rows="3"
                        >{{ old('short_description_es') }}</textarea>
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label for="short_description_en">
                            Descripción corta EN
                        </label>

                        <textarea
                            id="short_description_en"
                            name="short_description_en"
                            class="form-control"
                            rows="3"
                        >{{ old('short_description_en') }}</textarea>
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label for="description_es">
                            Descripción completa ES
                        </label>

                        <textarea
                            id="description_es"
                            name="description_es"
                            class="form-control"
                            rows="6"
                        >{{ old('description_es') }}</textarea>
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label for="description_en">
                            Descripción completa EN
                        </label>

                        <textarea
                            id="description_en"
                            name="description_en"
                            class="form-control"
                            rows="6"
                        >{{ old('description_en') }}</textarea>
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label>
                            Destacado
                        </label>

                        <input
                            type="hidden"
                            name="is_featured"
                            value="0"
                        >

                        <label class="nv-admin-toggle">
                            <input
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                @checked(
                                    old('is_featured')
                                )
                            >

                            <span>
                                Marcar como producto destacado
                            </span>
                        </label>
                    </div>
                </div>
            </section>

            {{-- VARIANTES --}}
            <section class="nv-admin-form-card">
                <div class="nv-admin-form-card-header">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                        <div>
                            <h2>
                                Variantes y especificaciones
                            </h2>

                            <p>
                                Crea todas las presentaciones
                                del producto.
                            </p>
                        </div>

                        <button
                            type="button"
                            class="nv-button nv-button-outline"
                            data-add-create-variant
                        >
                            + Agregar variante
                        </button>
                    </div>
                </div>

                <div
                    class="nv-admin-variants"
                    data-create-variants
                    data-next-variant-key="{{ $nextVariantKey }}"
                >
                    @foreach ($oldVariants as $variantKey => $variant)
                        @php
                            $specifications =
                                $variant['specifications']
                                ?? [
                                    [
                                        'key' => '',
                                        'value' => '',
                                    ],
                                ];
                        @endphp

                        <article
                            class="nv-admin-variant-card"
                            data-create-variant
                            data-variant-key="{{ $variantKey }}"
                        >
                            <div class="nv-admin-variant-header">
                                <div>
                                    <span>
                                        Nueva variante
                                    </span>

                                    <strong>
                                        {{ $variant['name_es']
                                            ?: 'Variante '.($loop->iteration) }}
                                    </strong>
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    data-remove-create-variant
                                >
                                    Eliminar variante
                                </button>
                            </div>

                            <div class="nv-admin-form-grid">
                                <div class="nv-admin-field">
                                    <label>
                                        SKU *
                                    </label>

                                    <input
                                        type="text"
                                        name="variants[{{ $variantKey }}][sku]"
                                        class="form-control"
                                        value="{{ $variant['sku'] ?? '' }}"
                                        maxlength="100"
                                        data-create-variant-sku
                                        required
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Nombre ES *
                                    </label>

                                    <input
                                        type="text"
                                        name="variants[{{ $variantKey }}][name_es]"
                                        class="form-control"
                                        value="{{ $variant['name_es'] ?? '' }}"
                                        maxlength="255"
                                        data-create-variant-name
                                        required
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Nombre EN
                                    </label>

                                    <input
                                        type="text"
                                        name="variants[{{ $variantKey }}][name_en]"
                                        class="form-control"
                                        value="{{ $variant['name_en'] ?? '' }}"
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Precio
                                    </label>

                                    <input
                                        type="number"
                                        name="variants[{{ $variantKey }}][price]"
                                        class="form-control"
                                        step="0.01"
                                        min="0"
                                        value="{{ $variant['price'] ?? '' }}"
                                        placeholder="Pendiente"
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Moneda *
                                    </label>

                                    <input
                                        type="text"
                                        name="variants[{{ $variantKey }}][currency]"
                                        class="form-control"
                                        maxlength="3"
                                        value="{{ $variant['currency'] ?? 'COP' }}"
                                        required
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Estado de stock *
                                    </label>

                                    <select
                                        name="variants[{{ $variantKey }}][stock_status]"
                                        class="form-select"
                                        required
                                    >
                                        @foreach ([
                                            'unknown' => 'Pendiente / desconocido',
                                            'in_stock' => 'Disponible',
                                            'out_of_stock' => 'Agotado',
                                            'backorder' => 'Bajo pedido',
                                        ] as $stockValue => $stockLabel)
                                            <option
                                                value="{{ $stockValue }}"
                                                @selected(
                                                    ($variant['stock_status'] ?? 'unknown')
                                                    === $stockValue
                                                )
                                            >
                                                {{ $stockLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Cantidad disponible
                                    </label>

                                    <input
                                        type="number"
                                        name="variants[{{ $variantKey }}][stock_quantity]"
                                        class="form-control"
                                        min="0"
                                        value="{{ $variant['stock_quantity'] ?? '' }}"
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Orden
                                    </label>

                                    <input
                                        type="number"
                                        name="variants[{{ $variantKey }}][sort_order]"
                                        class="form-control"
                                        min="0"
                                        max="9999"
                                        value="{{ $variant['sort_order'] ?? 0 }}"
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <input
                                        type="hidden"
                                        name="variants[{{ $variantKey }}][manage_stock]"
                                        value="0"
                                    >

                                    <label class="nv-admin-toggle">
                                        <input
                                            type="checkbox"
                                            name="variants[{{ $variantKey }}][manage_stock]"
                                            value="1"
                                            @checked(
                                                $variant['manage_stock']
                                                ?? false
                                            )
                                        >

                                        <span>
                                            Gestionar stock
                                        </span>
                                    </label>
                                </div>

                                <div class="nv-admin-field">
                                    <input
                                        type="hidden"
                                        name="variants[{{ $variantKey }}][is_default]"
                                        value="0"
                                    >

                                    <label class="nv-admin-toggle">
                                        <input
                                            type="checkbox"
                                            name="variants[{{ $variantKey }}][is_default]"
                                            value="1"
                                            @checked(
                                                $variant['is_default']
                                                ?? false
                                            )
                                        >

                                        <span>
                                            Variante predeterminada
                                        </span>
                                    </label>
                                </div>

                                <div class="nv-admin-field">
                                    <input
                                        type="hidden"
                                        name="variants[{{ $variantKey }}][is_active]"
                                        value="0"
                                    >

                                    <label class="nv-admin-toggle">
                                        <input
                                            type="checkbox"
                                            name="variants[{{ $variantKey }}][is_active]"
                                            value="1"
                                            @checked(
                                                $variant['is_active']
                                                ?? true
                                            )
                                        >

                                        <span>
                                            Variante activa
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="nv-admin-specifications">
                                <div class="nv-admin-specifications-header">
                                    <div>
                                        <h3>
                                            Especificaciones técnicas
                                        </h3>

                                        <p>
                                            Aumento, objetivo, cristal,
                                            peso, campo visual o cualquier
                                            otro atributo.
                                        </p>
                                    </div>

                                    <button
                                        type="button"
                                        class="nv-admin-action-link"
                                        data-add-spec
                                        data-variant-key="{{ $variantKey }}"
                                    >
                                        + Agregar atributo
                                    </button>
                                </div>

                                <div
                                    class="nv-admin-spec-list"
                                    data-spec-list="{{ $variantKey }}"
                                    data-variant-key="{{ $variantKey }}"
                                >
                                    @foreach ($specifications as $specIndex => $specification)
                                        <div
                                            class="nv-admin-spec-row"
                                            data-spec-row
                                        >
                                            <div class="nv-admin-field">
                                                <label>
                                                    Especificación
                                                </label>

                                                <input
                                                    type="text"
                                                    name="variants[{{ $variantKey }}][specifications][{{ $specIndex }}][key]"
                                                    class="form-control"
                                                    value="{{ $specification['key'] ?? '' }}"
                                                    maxlength="100"
                                                    placeholder="Ej: magnification"
                                                    data-spec-key
                                                >
                                            </div>

                                            <div class="nv-admin-field">
                                                <label>
                                                    Valor
                                                </label>

                                                <input
                                                    type="text"
                                                    name="variants[{{ $variantKey }}][specifications][{{ $specIndex }}][value]"
                                                    class="form-control"
                                                    value="{{ $specification['value'] ?? '' }}"
                                                    maxlength="500"
                                                    placeholder="Ej: 8x"
                                                    data-spec-value
                                                >
                                            </div>

                                            <div class="nv-admin-spec-remove-wrap">
                                                <button
                                                    type="button"
                                                    class="nv-admin-spec-remove"
                                                    data-remove-spec
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            {{-- IMÁGENES --}}
            <section class="nv-admin-form-card">
                <div class="nv-admin-form-card-header">
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                        <div>
                            <h2>
                                Imágenes y galería
                            </h2>

                            <p>
                                Puedes cargar la imagen principal,
                                galería y fotografías específicas
                                de cada variante.
                            </p>
                        </div>

                        <button
                            type="button"
                            class="nv-button nv-button-outline"
                            data-add-create-image
                        >
                            + Agregar imagen
                        </button>
                    </div>
                </div>

                <div
                    class="nv-admin-variants"
                    data-create-images
                    data-next-image-key="0"
                >
                    <div class="nv-admin-empty">
                        Las imágenes son opcionales.
                        Pulsa “Agregar imagen” para comenzar.
                    </div>
                </div>
            </section>

            <div class="nv-admin-form-actions">
                <a
                    href="{{ route('admin.products.index') }}"
                    class="nv-button nv-button-outline"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="nv-button nv-button-primary"
                >
                    Crear producto completo
                </button>
            </div>
        </form>
    </div>
@endsection