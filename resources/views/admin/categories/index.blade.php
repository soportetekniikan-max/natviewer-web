@extends('admin.layout')

@section('title', 'Categorías')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Catálogo
                </span>

                <h1>Categorías</h1>

                <p>
                    Organiza las familias de productos
                    disponibles en Natviewer.
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
                    <h2>Nueva categoría</h2>

                    <p>
                        Podrás usarla inmediatamente
                        al crear o editar productos.
                    </p>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('admin.categories.store') }}"
            >
                @csrf

                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label>Nombre ES *</label>

                        <input
                            type="text"
                            name="name_es"
                            class="form-control"
                            value="{{ old('name_es') }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Nombre EN</label>

                        <input
                            type="text"
                            name="name_en"
                            class="form-control"
                            value="{{ old('name_en') }}"
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Slug</label>

                        <input
                            type="text"
                            name="slug"
                            class="form-control"
                            value="{{ old('slug') }}"
                            placeholder="binoculares-terrestres"
                        >

                        <small>
                            Vacío = generado automáticamente.
                        </small>
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

                            <span>Categoría activa</span>
                        </label>
                    </div>
                </div>

                <div class="nv-admin-form-actions px-4">
                    <button
                        type="submit"
                        class="nv-button nv-button-primary"
                    >
                        Crear categoría
                    </button>
                </div>
            </form>
        </section>

        <section class="nv-admin-panel">
            <div class="nv-admin-panel-header">
                <div>
                    <h2>Categorías existentes</h2>

                    <p>
                        {{ $categories->count() }}
                        categoría(s).
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Orden</th>
                            <th>Categoría</th>
                            <th>Slug</th>
                            <th>Productos</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>
                                    {{ $category->sort_order }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $category->name_es }}
                                    </strong>

                                    @if ($category->name_en)
                                        <div>
                                            <small>
                                                {{ $category->name_en }}
                                            </small>
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $category->slug }}
                                </td>

                                <td>
                                    {{ $category->products_count }}
                                </td>

                                <td>
                                    {{ $category->is_active
                                        ? 'Activa'
                                        : 'Inactiva' }}
                                </td>

                                <td>
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a
                                            href="{{ route(
                                                'admin.categories.edit',
                                                $category
                                            ) }}"
                                            class="nv-admin-action-link"
                                        >
                                            Editar
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.categories.toggle',
                                                $category
                                            ) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                {{ $category->is_active
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