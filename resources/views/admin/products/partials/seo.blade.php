<section
    id="seo"
    class="nv-admin-form-card"
>
    <div class="nv-admin-form-card-header">
        <div>
            <h2>
                SEO
            </h2>

            <p>
                Configura cómo puede aparecer este producto
                en buscadores y resultados compartidos.
                Si dejas un campo vacío podremos utilizar
                posteriormente el contenido del producto
                como fallback.
            </p>
        </div>
    </div>

    <div class="nv-admin-form-grid">
        @isset($product)
            <div class="nv-admin-field nv-admin-field-full">
                <label for="slug">
                    Slug
                </label>

                <input
                    type="text"
                    id="slug"
                    name="slug"
                    class="form-control"
                    maxlength="255"
                    value="{{ old(
                        'slug',
                        $product->slug
                    ) }}"
                    required
                >

                <small>
                    URL amigable del producto.
                    Usa letras minúsculas, números
                    y guiones.
                </small>
            </div>
        @else
            <div class="nv-admin-field nv-admin-field-full">
                <label for="slug">
                    Slug
                </label>

                <input
                    type="text"
                    id="slug"
                    name="slug"
                    class="form-control"
                    maxlength="255"
                    value="{{ old('slug') }}"
                    placeholder="natviewer-falco"
                >

                <small>
                    Opcional. Si lo dejas vacío,
                    se genera automáticamente
                    a partir del nombre.
                </small>
            </div>
        @endisset

        <div class="nv-admin-field nv-admin-field-full">
            <label for="meta_title_es">
                Meta title ES
            </label>

            <input
                type="text"
                id="meta_title_es"
                name="meta_title_es"
                class="form-control"
                maxlength="255"
                value="{{ old(
                    'meta_title_es',
                    isset($product)
                        ? $product->meta_title_es
                        : null
                ) }}"
                placeholder="Ej: Binoculares Natviewer Falco 8×42 UD"
            >

            <small>
                Recomendación editorial:
                aproximadamente 50–60 caracteres.
                No es un límite técnico de Google.
            </small>
        </div>

        <div class="nv-admin-field nv-admin-field-full">
            <label for="meta_description_es">
                Meta description ES
            </label>

            <textarea
                id="meta_description_es"
                name="meta_description_es"
                class="form-control"
                rows="4"
                maxlength="1000"
                placeholder="Describe el producto de forma clara para los resultados de búsqueda."
            >{{ old(
                'meta_description_es',
                isset($product)
                    ? $product->meta_description_es
                    : null
            ) }}</textarea>

            <small>
                Recomendación editorial:
                aproximadamente 140–160 caracteres.
            </small>
        </div>

        <div class="nv-admin-field nv-admin-field-full">
            <label for="meta_title_en">
                Meta title EN
            </label>

            <input
                type="text"
                id="meta_title_en"
                name="meta_title_en"
                class="form-control"
                maxlength="255"
                value="{{ old(
                    'meta_title_en',
                    isset($product)
                        ? $product->meta_title_en
                        : null
                ) }}"
                placeholder="Example: Natviewer Falco 8×42 UD Binoculars"
            >

            <small>
                Recomendación editorial:
                aproximadamente 50–60 caracteres.
            </small>
        </div>

        <div class="nv-admin-field nv-admin-field-full">
            <label for="meta_description_en">
                Meta description EN
            </label>

            <textarea
                id="meta_description_en"
                name="meta_description_en"
                class="form-control"
                rows="4"
                maxlength="1000"
                placeholder="Describe the product clearly for search results."
            >{{ old(
                'meta_description_en',
                isset($product)
                    ? $product->meta_description_en
                    : null
            ) }}</textarea>

            <small>
                Recomendación editorial:
                aproximadamente 140–160 caracteres.
            </small>
        </div>
    </div>
</section>