@extends('admin.layout')

@section('title', 'Editar categoría')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Categorías
                </span>

                <h1>Editar categoría</h1>

                <p>
                    {{ $category->name_es }} ·
                    {{ $category->products_count }} producto(s)
                </p>
            </div>

            <a
                href="{{ route('admin.categories.index') }}"
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
                'admin.categories.update',
                $category
            ) }}"
            class="nv-admin-product-form"
        >
            @csrf
            @method('PUT')

            <section class="nv-admin-form-card">
                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label>Nombre ES *</label>

                        <input
                            type="text"
                            name="name_es"
                            class="form-control"
                            value="{{ old(
                                'name_es',
                                $category->name_es
                            ) }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label>Nombre EN</label>

                        <input
                            type="text"
                            name="name_en"
                            class="form-control"
                            value="{{ old(
                                'name_en',
                                $category->name_en
                            ) }}"
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
                                $category->slug
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
                                $category->sort_order
                            ) }}"
                        >
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label>Descripción ES</label>

                        <textarea
                            name="description_es"
                            class="form-control"
                            rows="5"
                        >{{ old(
                            'description_es',
                            $category->description_es
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
                            $category->description_en
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
                                    $category->is_active
                                ))
                            >

                            <span>Categoría activa</span>
                        </label>
                    </div>
                </div>
            </section>

            <div class="nv-admin-form-actions">
                <a
                    href="{{ route('admin.categories.index') }}"
                    class="nv-button nv-button-outline"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="nv-button nv-button-primary"
                >
                    Guardar categoría
                </button>
            </div>
        </form>
    </div>
@endsection