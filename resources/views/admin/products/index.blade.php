@extends('admin.layout')

@section('title', 'Productos')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header nv-admin-page-header-actions">
            <div>
                <span class="nv-eyebrow">
                    Catálogo
                </span>

                <h1>Productos</h1>

                <p>
                    Gestiona productos, variantes,
                    imágenes, precios y stock.
                </p>
            </div>

            <a
                href="{{ route('admin.products.create') }}"
                class="nv-button nv-button-primary"
            >
                + Nuevo producto
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <section class="nv-admin-panel">
            <div class="nv-admin-panel-header">
                <div>
                    <h2>Catálogo actual</h2>

                    <p>
                        {{ $products->total() }}
                        producto(s) registrado(s).
                    </p>
                </div>
            </div>

            @if ($products->isEmpty())
                <div class="nv-admin-empty">
                    No hay productos registrados.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Marca</th>
                                <th>Categoría</th>
                                <th>Variantes</th>
                                <th>Imágenes</th>
                                <th>Estado</th>
                                <th>Destacado</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <div class="nv-admin-product-name">
                                            <strong>
                                                {{ $product->name_es }}
                                            </strong>

                                            <span>
                                                {{ $product->slug }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        {{ $product->brand?->name ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $product->category?->name_es ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $product->variants_count }}
                                    </td>

                                    <td>
                                        {{ $product->images_count }}
                                    </td>

                                    <td>
                                        <span
                                            class="nv-admin-status nv-admin-status-{{ $product->status }}"
                                        >
                                            {{ $product->status }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $product->is_featured ? 'Sí' : 'No' }}
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a
                                                href="{{ route('admin.products.edit', $product) }}"
                                                class="nv-admin-action-link"
                                            >
                                                Editar
                                            </a>

                                            @if ($product->status !== 'archived')
                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.products.archive', $product) }}"
                                                    onsubmit="return confirm('¿Archivar este producto? No se eliminarán sus datos, imágenes, variantes ni cotizaciones.');"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-secondary"
                                                    >
                                                        Archivar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($products->hasPages())
                    <div class="nv-admin-pagination">
                        {{ $products->links() }}
                    </div>
                @endif
            @endif
        </section>
    </div>
@endsection