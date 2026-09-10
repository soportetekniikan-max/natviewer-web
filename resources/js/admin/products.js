const escapeHtml = (value) => {
    const div = document.createElement('div');

    div.textContent = value ?? '';

    return div.innerHTML;
};

const reindexSpecificationRows = (list) => {
    const variantKey =
        list.dataset.variantId
        || list.dataset.variantKey;

    if (variantKey === undefined) {
        return;
    }

    const rows = Array.from(
        list.querySelectorAll(
            '[data-spec-row]'
        )
    );

    rows.forEach((row, index) => {
        const keyInput = row.querySelector(
            '[data-spec-key]'
        );

        const valueInput =
            row.querySelector(
                '[data-spec-value]'
            );

        if (keyInput) {
            keyInput.name =
                `variants[${variantKey}]` +
                `[specifications]` +
                `[${index}][key]`;
        }

        if (valueInput) {
            valueInput.name =
                `variants[${variantKey}]` +
                `[specifications]` +
                `[${index}][value]`;
        }
    });
};

const createSpecificationRow = () => {
    const row =
        document.createElement('div');

    row.className =
        'nv-admin-spec-row';

    row.dataset.specRow = '';

    row.innerHTML = `
        <div class="nv-admin-field">
            <label>Especificación</label>

            <input
                type="text"
                class="form-control"
                data-spec-key
                maxlength="100"
                placeholder="Ej: weight"
            >
        </div>

        <div class="nv-admin-field">
            <label>Valor</label>

            <input
                type="text"
                class="form-control"
                data-spec-value
                maxlength="500"
                placeholder="Ej: 650 g"
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
    `;

    return row;
};

const refreshImageVariantOptions = () => {
    const variantRows = Array.from(
        document.querySelectorAll(
            '[data-create-variant]'
        )
    );

    const variants =
        variantRows.map((row) => {
            const key =
                row.dataset.variantKey;

            const name =
                row.querySelector(
                    '[data-create-variant-name]'
                )?.value?.trim();

            const sku =
                row.querySelector(
                    '[data-create-variant-sku]'
                )?.value?.trim();

            return {
                key,
                label:
                    name
                    || sku
                    || `Variante ${key}`,
            };
        });

    document
        .querySelectorAll(
            '[data-image-variant-select]'
        )
        .forEach((select) => {
            const current =
                select.value;

            select.innerHTML =
                '<option value="">' +
                'Imagen general del producto' +
                '</option>';

            variants.forEach(
                (variant) => {
                    const option =
                        document.createElement(
                            'option'
                        );

                    option.value =
                        variant.key;

                    option.textContent =
                        variant.label;

                    select.appendChild(
                        option
                    );
                }
            );

            if (
                variants.some(
                    (variant) =>
                        variant.key
                        === current
                )
            ) {
                select.value =
                    current;
            }
        });
};

const createVariantRow = (
    variantKey
) => {
    const article =
        document.createElement(
            'article'
        );

    article.className =
        'nv-admin-variant-card';

    article.dataset.createVariant = '';
    article.dataset.variantKey =
        variantKey;

    article.innerHTML = `
        <div class="nv-admin-variant-header">
            <div>
                <span>Nueva variante</span>

                <strong>
                    Variante ${escapeHtml(
                        variantKey
                    )}
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
                <label>SKU *</label>

                <input
                    type="text"
                    name="variants[${variantKey}][sku]"
                    class="form-control"
                    maxlength="100"
                    data-create-variant-sku
                    required
                >
            </div>

            <div class="nv-admin-field">
                <label>Nombre ES *</label>

                <input
                    type="text"
                    name="variants[${variantKey}][name_es]"
                    class="form-control"
                    maxlength="255"
                    data-create-variant-name
                    required
                >
            </div>

            <div class="nv-admin-field">
                <label>Nombre EN</label>

                <input
                    type="text"
                    name="variants[${variantKey}][name_en]"
                    class="form-control"
                    maxlength="255"
                >
            </div>

            <div class="nv-admin-field">
                <label>Precio</label>

                <input
                    type="number"
                    name="variants[${variantKey}][price]"
                    class="form-control"
                    step="0.01"
                    min="0"
                    placeholder="Pendiente"
                >
            </div>

            <div class="nv-admin-field">
                <label>Moneda *</label>

                <input
                    type="text"
                    name="variants[${variantKey}][currency]"
                    class="form-control"
                    maxlength="3"
                    value="COP"
                    required
                >
            </div>

            <div class="nv-admin-field">
                <label>Estado de stock *</label>

                <select
                    name="variants[${variantKey}][stock_status]"
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
                <label>Cantidad disponible</label>

                <input
                    type="number"
                    name="variants[${variantKey}][stock_quantity]"
                    class="form-control"
                    min="0"
                >
            </div>

            <div class="nv-admin-field">
                <label>Orden</label>

                <input
                    type="number"
                    name="variants[${variantKey}][sort_order]"
                    class="form-control"
                    min="0"
                    max="9999"
                    value="0"
                >
            </div>

            <div class="nv-admin-field">
                <input
                    type="hidden"
                    name="variants[${variantKey}][manage_stock]"
                    value="0"
                >

                <label class="nv-admin-toggle">
                    <input
                        type="checkbox"
                        name="variants[${variantKey}][manage_stock]"
                        value="1"
                    >

                    <span>
                        Gestionar stock
                    </span>
                </label>
            </div>

            <div class="nv-admin-field">
                <input
                    type="hidden"
                    name="variants[${variantKey}][is_default]"
                    value="0"
                >

                <label class="nv-admin-toggle">
                    <input
                        type="checkbox"
                        name="variants[${variantKey}][is_default]"
                        value="1"
                    >

                    <span>
                        Variante predeterminada
                    </span>
                </label>
            </div>

            <div class="nv-admin-field">
                <input
                    type="hidden"
                    name="variants[${variantKey}][is_active]"
                    value="0"
                >

                <label class="nv-admin-toggle">
                    <input
                        type="checkbox"
                        name="variants[${variantKey}][is_active]"
                        value="1"
                        checked
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
                    <h3>Especificaciones técnicas</h3>

                    <p>
                        Agrega los atributos técnicos
                        necesarios.
                    </p>
                </div>

                <button
                    type="button"
                    class="nv-admin-action-link"
                    data-add-spec
                    data-variant-key="${variantKey}"
                >
                    + Agregar atributo
                </button>
            </div>

            <div
                class="nv-admin-spec-list"
                data-spec-list="${variantKey}"
                data-variant-key="${variantKey}"
            >
            </div>
        </div>
    `;

    return article;
};

const createImageRow = (
    imageKey
) => {
    const article =
        document.createElement(
            'article'
        );

    article.className =
        'nv-admin-variant-card';

    article.dataset.createImage = '';
    article.dataset.imageKey =
        imageKey;

    article.innerHTML = `
        <div class="nv-admin-variant-header">
            <div>
                <span>Galería</span>

                <strong>
                    Imagen ${escapeHtml(
                        imageKey
                    )}
                </strong>
            </div>

            <button
                type="button"
                class="btn btn-sm btn-outline-danger"
                data-remove-create-image
            >
                Eliminar imagen
            </button>
        </div>

        <div class="nv-admin-form-grid">
            <div class="nv-admin-field nv-admin-field-full">
                <label>Archivo *</label>

                <input
                    type="file"
                    name="images[${imageKey}][file]"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    required
                >

                <small>
                    JPG, JPEG, PNG o WebP.
                    Máximo 8 MB.
                </small>
            </div>

            <div class="nv-admin-field">
                <label>ALT ES</label>

                <input
                    type="text"
                    name="images[${imageKey}][alt_es]"
                    class="form-control"
                    maxlength="255"
                >
            </div>

            <div class="nv-admin-field">
                <label>ALT EN</label>

                <input
                    type="text"
                    name="images[${imageKey}][alt_en]"
                    class="form-control"
                    maxlength="255"
                >
            </div>

            <div class="nv-admin-field">
                <label>Variante asociada</label>

                <select
                    name="images[${imageKey}][variant_key]"
                    class="form-select"
                    data-image-variant-select
                >
                    <option value="">
                        Imagen general del producto
                    </option>
                </select>
            </div>

            <div class="nv-admin-field">
                <label>Orden</label>

                <input
                    type="number"
                    name="images[${imageKey}][sort_order]"
                    class="form-control"
                    min="0"
                    max="9999"
                    value="${(
                        Number(imageKey) + 1
                    ) * 10}"
                >
            </div>

            <div class="nv-admin-field nv-admin-field-full">
                <input
                    type="hidden"
                    name="images[${imageKey}][is_primary]"
                    value="0"
                >

                <label class="nv-admin-toggle">
                    <input
                        type="checkbox"
                        name="images[${imageKey}][is_primary]"
                        value="1"
                    >

                    <span>
                        Usar como imagen principal
                    </span>
                </label>
            </div>
        </div>
    `;

    return article;
};

document.addEventListener(
    'DOMContentLoaded',
    () => {
        /*
        |--------------------------------------------------------------------------
        | Especificaciones
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'click',
            (event) => {
                const button =
                    event.target.closest(
                        '[data-add-spec]'
                    );

                if (!button) {
                    return;
                }

                const variantKey =
                    button.dataset.variantId
                    || button.dataset.variantKey;

                const list =
                    document.querySelector(
                        `[data-spec-list="${variantKey}"]`
                    );

                if (!list) {
                    return;
                }

                list.appendChild(
                    createSpecificationRow()
                );

                reindexSpecificationRows(
                    list
                );
            }
        );

        document.addEventListener(
            'click',
            (event) => {
                const button =
                    event.target.closest(
                        '[data-remove-spec]'
                    );

                if (!button) {
                    return;
                }

                const row =
                    button.closest(
                        '[data-spec-row]'
                    );

                const list =
                    button.closest(
                        '[data-spec-list]'
                    );

                if (!row || !list) {
                    return;
                }

                row.remove();

                reindexSpecificationRows(
                    list
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Crear variantes
        |--------------------------------------------------------------------------
        */

        const variantsContainer =
            document.querySelector(
                '[data-create-variants]'
            );

        const addVariantButton =
            document.querySelector(
                '[data-add-create-variant]'
            );

        let nextVariantKey =
            Number(
                variantsContainer
                    ?.dataset
                    .nextVariantKey
                || 1
            );

        if (
            variantsContainer
            && addVariantButton
        ) {
            addVariantButton
                .addEventListener(
                    'click',
                    () => {
                        const key =
                            nextVariantKey++;

                        const row =
                            createVariantRow(
                                key
                            );

                        variantsContainer
                            .appendChild(
                                row
                            );

                        const specList =
                            row.querySelector(
                                '[data-spec-list]'
                            );

                        if (specList) {
                            specList.appendChild(
                                createSpecificationRow()
                            );

                            reindexSpecificationRows(
                                specList
                            );
                        }

                        refreshImageVariantOptions();
                    }
                );
        }

        document.addEventListener(
            'click',
            (event) => {
                const button =
                    event.target.closest(
                        '[data-remove-create-variant]'
                    );

                if (!button) {
                    return;
                }

                const rows =
                    document.querySelectorAll(
                        '[data-create-variant]'
                    );

                if (rows.length <= 1) {
                    window.alert(
                        'El producto debe tener al menos una variante.'
                    );

                    return;
                }

                button
                    .closest(
                        '[data-create-variant]'
                    )
                    ?.remove();

                refreshImageVariantOptions();
            }
        );

        document.addEventListener(
            'input',
            (event) => {
                if (
                    event.target.matches(
                        '[data-create-variant-name], [data-create-variant-sku]'
                    )
                ) {
                    refreshImageVariantOptions();
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Crear imágenes
        |--------------------------------------------------------------------------
        */

        const imagesContainer =
            document.querySelector(
                '[data-create-images]'
            );

        const addImageButton =
            document.querySelector(
                '[data-add-create-image]'
            );

        let nextImageKey =
            Number(
                imagesContainer
                    ?.dataset
                    .nextImageKey
                || 0
            );

        if (
            imagesContainer
            && addImageButton
        ) {
            addImageButton
                .addEventListener(
                    'click',
                    () => {
                        const key =
                            nextImageKey++;

                        imagesContainer
                            .appendChild(
                                createImageRow(
                                    key
                                )
                            );

                        refreshImageVariantOptions();
                    }
                );
        }

        document.addEventListener(
            'click',
            (event) => {
                const button =
                    event.target.closest(
                        '[data-remove-create-image]'
                    );

                if (!button) {
                    return;
                }

                button
                    .closest(
                        '[data-create-image]'
                    )
                    ?.remove();
            }
        );

        /*
         * Solo una imagen principal en creación.
         */
        document.addEventListener(
            'change',
            (event) => {
                const checkbox =
                    event.target;

                if (
                    !checkbox.matches(
                        'input[name^="images"][name$="[is_primary]"]'
                    )
                    || !checkbox.checked
                ) {
                    return;
                }

                document
                    .querySelectorAll(
                        'input[name^="images"][name$="[is_primary]"]'
                    )
                    .forEach(
                        (other) => {
                            if (
                                other !== checkbox
                            ) {
                                other.checked =
                                    false;
                            }
                        }
                    );
            }
        );

        refreshImageVariantOptions();
    }
);