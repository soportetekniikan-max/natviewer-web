@extends('admin.layout')

@section('title', 'Editar producto')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Catálogo
                </span>

                <h1>Editar producto</h1>

                <p>
                    {{ $product->name_es }}
                </p>
            </div>

            <a
                href="{{ route('admin.products.index') }}"
                class="nv-button nv-button-outline"
            >
                Volver a productos
            </a>
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

        <form
            method="POST"
            action="{{ route('admin.products.update', $product) }}"
            class="nv-admin-product-form"
        >
            @csrf
            @method('PUT')

            <section class="nv-admin-form-card">
                <div class="nv-admin-form-card-header">
                    <div>
                        <h2>Información general</h2>

                        <p>
                            Información principal visible
                            en el catálogo.
                        </p>
                    </div>
                </div>

                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label for="name_es">
                            Nombre ES
                        </label>

                        <input
                            type="text"
                            id="name_es"
                            name="name_es"
                            class="form-control"
                            value="{{ old('name_es', $product->name_es) }}"
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
                            value="{{ old('name_en', $product->name_en) }}"
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label for="category_id">
                            Categoría
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            class="form-select"
                            required
                        >
                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(
                                        old(
                                            'category_id',
                                            $product->category_id
                                        ) == $category->id
                                    )
                                >
                                    {{ $category->name_es }}
                                    {{ ! $category->is_active ? ' (inactiva)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="nv-admin-field">
                        <label for="brand_id">
                            Marca
                        </label>

                        <select
                            id="brand_id"
                            name="brand_id"
                            class="form-select"
                            required
                        >
                            @foreach ($brands as $brand)
                                <option
                                    value="{{ $brand->id }}"
                                    @selected(
                                        old(
                                            'brand_id',
                                            $product->brand_id
                                        ) == $brand->id
                                    )
                                >
                                    {{ $brand->name }}
                                    {{ ! $brand->is_active ? ' (inactiva)' : '' }}
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
                        >{{ old('short_description_es', $product->short_description_es) }}</textarea>
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
                        >{{ old('short_description_en', $product->short_description_en) }}</textarea>
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label for="description_es">
                            Descripción completa ES
                        </label>

                        <textarea
                            id="description_es"
                            name="description_es"
                            class="form-control"
                            rows="5"
                        >{{ old('description_es', $product->description_es) }}</textarea>
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label for="description_en">
                            Descripción completa EN
                        </label>

                        <textarea
                            id="description_en"
                            name="description_en"
                            class="form-control"
                            rows="5"
                        >{{ old('description_en', $product->description_en) }}</textarea>
                    </div>

                    <div class="nv-admin-field">
                        <label for="status">
                            Estado
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-select"
                            required
                        >
                            <option
                                value="draft"
                                @selected(old('status', $product->status) === 'draft')
                            >
                                Borrador
                            </option>

                            <option
                                value="published"
                                @selected(old('status', $product->status) === 'published')
                            >
                                Publicado
                            </option>

                            <option
                                value="archived"
                                @selected(old('status', $product->status) === 'archived')
                            >
                                Archivado
                            </option>
                        </select>
                    </div>

                    <div class="nv-admin-field">
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
                                    old(
                                        'is_featured',
                                        $product->is_featured
                                    )
                                )
                            >

                            <span>
                                Mostrar como producto destacado
                            </span>
                        </label>
                    </div>
                </div>
            </section>

            <section class="nv-admin-form-card">
                <div class="nv-admin-form-card-header">
                    <div>
                        <h2>Variantes</h2>

                        <p>
                            Gestiona precio, moneda,
                            disponibilidad y stock.
                        </p>
                    </div>
                </div>

                <div class="nv-admin-variants">
                    @foreach ($product->variants as $variant)
                        @php
                            $oldVariant = old(
                                'variants.' . $variant->id,
                                []
                            );
                        @endphp

                        <article class="nv-admin-variant-card">
                            <div class="nv-admin-variant-header">
                                <div>
                                    <span>SKU</span>

                                    <strong>
                                        {{ $variant->sku }}
                                    </strong>
                                </div>

                                <span>
                                    {{ $variant->is_default
                                        ? 'Predeterminada'
                                        : 'Variante' }}
                                </span>
                            </div>

                            <input
                                type="hidden"
                                name="variants[{{ $variant->id }}][id]"
                                value="{{ $variant->id }}"
                            >

                            <div class="nv-admin-form-grid">
                                <div class="nv-admin-field">
                                    <label>
                                        Nombre ES
                                    </label>

                                    <input
                                        type="text"
                                        name="variants[{{ $variant->id }}][name_es]"
                                        class="form-control"
                                        value="{{ $oldVariant['name_es'] ?? $variant->name_es }}"
                                        required
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Nombre EN
                                    </label>

                                    <input
                                        type="text"
                                        name="variants[{{ $variant->id }}][name_en]"
                                        class="form-control"
                                        value="{{ $oldVariant['name_en'] ?? $variant->name_en }}"
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Precio
                                    </label>

                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="variants[{{ $variant->id }}][price]"
                                        class="form-control"
                                        value="{{ $oldVariant['price'] ?? $variant->price }}"
                                        placeholder="Pendiente"
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Moneda
                                    </label>

                                    <input
                                        type="text"
                                        maxlength="3"
                                        name="variants[{{ $variant->id }}][currency]"
                                        class="form-control"
                                        value="{{ $oldVariant['currency'] ?? $variant->currency }}"
                                        required
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Estado de stock
                                    </label>

                                    <select
                                        name="variants[{{ $variant->id }}][stock_status]"
                                        class="form-select"
                                        required
                                    >
                                        @php
                                            $currentStockStatus =
                                                $oldVariant['stock_status']
                                                ?? $variant->stock_status;
                                        @endphp

                                        <option
                                            value="unknown"
                                            @selected($currentStockStatus === 'unknown')
                                        >
                                            Pendiente / desconocido
                                        </option>

                                        <option
                                            value="in_stock"
                                            @selected($currentStockStatus === 'in_stock')
                                        >
                                            Disponible
                                        </option>

                                        <option
                                            value="out_of_stock"
                                            @selected($currentStockStatus === 'out_of_stock')
                                        >
                                            Agotado
                                        </option>

                                        <option
                                            value="backorder"
                                            @selected($currentStockStatus === 'backorder')
                                        >
                                            Bajo pedido
                                        </option>
                                    </select>
                                </div>

                                <div class="nv-admin-field">
                                    <label>
                                        Cantidad disponible
                                    </label>

                                    <input
                                        type="number"
                                        min="0"
                                        name="variants[{{ $variant->id }}][stock_quantity]"
                                        class="form-control"
                                        value="{{ $oldVariant['stock_quantity'] ?? $variant->stock_quantity }}"
                                        placeholder="Sin definir"
                                    >
                                </div>

                                <div class="nv-admin-field">
                                    <input
                                        type="hidden"
                                        name="variants[{{ $variant->id }}][manage_stock]"
                                        value="0"
                                    >

                                    <label class="nv-admin-toggle">
                                        <input
                                            type="checkbox"
                                            name="variants[{{ $variant->id }}][manage_stock]"
                                            value="1"
                                            @checked(
                                                $oldVariant
                                                    ? ($oldVariant['manage_stock'] ?? false)
                                                    : $variant->manage_stock
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
                                        name="variants[{{ $variant->id }}][is_active]"
                                        value="0"
                                    >

                                    <label class="nv-admin-toggle">
                                        <input
                                            type="checkbox"
                                            name="variants[{{ $variant->id }}][is_active]"
                                            value="1"
                                            @checked(
                                                $oldVariant
                                                    ? ($oldVariant['is_active'] ?? false)
                                                    : $variant->is_active
                                            )
                                        >

                                        <span>
                                            Variante activa
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </article>
                    @endforeach
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
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
@endsection