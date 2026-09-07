@extends('admin.layout')

@section('title', 'Editar marca')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Marcas
                </span>

                <h1>Editar marca</h1>

                <p>
                    {{ $brand->name }} ·
                    {{ $brand->products_count }} producto(s)
                </p>
            </div>

            <a
                href="{{ route('admin.brands.index') }}"
                class="nv-button nv-button-outline"
            >
                Volver
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
                'admin.brands.update',
                $brand
            ) }}"
            enctype="multipart/form-data"
            class="nv-admin-product-form"
        >
            @csrf
            @method('PUT')

            <section class="nv-admin-form-card">
                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label>Nombre *</label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old(
                                'name',
                                $brand->name
                            ) }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Slug</label>

                        <input
                            type="text"
                            name="slug"
                            class="form-control"
                            value="{{ old(
                                'slug',
                                $brand->slug
                            ) }}"
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Nuevo logo</label>

                        <input
                            type="file"
                            name="logo"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
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
                                $brand->sort_order
                            ) }}"
                        >
                    </div>

                    @if ($brand->logo_path)
                        <div class="nv-admin-field nv-admin-field-full">
                            <label>Logo actual</label>

                            <div class="mb-3">
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($brand->logo_path) }}"
                                    alt="{{ $brand->name }}"
                                    style="max-width: 220px; max-height: 100px; object-fit: contain;"
                                >
                            </div>

                            <input
                                type="hidden"
                                name="remove_logo"
                                value="0"
                            >

                            <label class="nv-admin-toggle">
                                <input
                                    type="checkbox"
                                    name="remove_logo"
                                    value="1"
                                    @checked(old('remove_logo'))
                                >

                                <span>Eliminar logo actual</span>
                            </label>
                        </div>
                    @else
                        <input
                            type="hidden"
                            name="remove_logo"
                            value="0"
                        >
                    @endif

                    <div class="nv-admin-field nv-admin-field-full">
                        <label>Descripción ES</label>

                        <textarea
                            name="description_es"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'description_es',
                            $brand->description_es
                        ) }}</textarea>
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label>Descripción EN</label>

                        <textarea
                            name="description_en"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'description_en',
                            $brand->description_en
                        ) }}</textarea>
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
                                @checked(old(
                                    'is_active',
                                    $brand->is_active
                                ))
                            >

                            <span>Marca activa</span>
                        </label>
                    </div>
                </div>
            </section>

            <div class="nv-admin-form-actions">
                <a
                    href="{{ route('admin.brands.index') }}"
                    class="nv-button nv-button-outline"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="nv-button nv-button-primary"
                >
                    Guardar marca
                </button>
            </div>
        </form>
    </div>
@endsection