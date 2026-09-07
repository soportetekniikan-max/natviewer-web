@extends('admin.layout')

@section('title', 'Marcas')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Catálogo
                </span>

                <h1>Marcas</h1>

                <p>
                    Administra fabricantes y marcas
                    comerciales del catálogo.
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
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="nv-admin-form-card mb-4">
            <div class="nv-admin-form-card-header">
                <div>
                    <h2>Nueva marca</h2>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('admin.brands.store') }}"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label>Nombre *</label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Slug</label>

                        <input
                            type="text"
                            name="slug"
                            class="form-control"
                            value="{{ old('slug') }}"
                            placeholder="natviewer"
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Logo</label>

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
                            value="{{ old('sort_order', 0) }}"
                        >
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label>Descripción ES</label>

                        <textarea
                            name="description_es"
                            class="form-control"
                            rows="4"
                        >{{ old('description_es') }}</textarea>
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label>Descripción EN</label>

                        <textarea
                            name="description_en"
                            class="form-control"
                            rows="4"
                        >{{ old('description_en') }}</textarea>
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

                            <span>Marca activa</span>
                        </label>
                    </div>
                </div>

                <div class="nv-admin-form-actions px-4">
                    <button
                        type="submit"
                        class="nv-button nv-button-primary"
                    >
                        Crear marca
                    </button>
                </div>
            </form>
        </section>

        <section class="nv-admin-panel">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Marca</th>
                            <th>Slug</th>
                            <th>Productos</th>
                            <th>Orden</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>
                                    @if ($brand->logo_path)
                                        <img
                                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($brand->logo_path) }}"
                                            alt="{{ $brand->name }}"
                                            style="width: 70px; height: 50px; object-fit: contain;"
                                        >
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    <strong>
                                        {{ $brand->name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $brand->slug }}
                                </td>

                                <td>
                                    {{ $brand->products_count }}
                                </td>

                                <td>
                                    {{ $brand->sort_order }}
                                </td>

                                <td>
                                    {{ $brand->is_active
                                        ? 'Activa'
                                        : 'Inactiva' }}
                                </td>

                                <td>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a
                                            href="{{ route(
                                                'admin.brands.edit',
                                                $brand
                                            ) }}"
                                            class="nv-admin-action-link"
                                        >
                                            Editar
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.brands.toggle',
                                                $brand
                                            ) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                {{ $brand->is_active
                                                    ? 'Desactivar'
                                                    : 'Activar' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection