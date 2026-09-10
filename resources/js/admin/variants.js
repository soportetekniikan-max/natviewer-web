const createVariantSpecificationRow = () => {
    const row = document.createElement('div');

    row.className = 'nv-admin-spec-row';
    row.dataset.variantSpecRow = '';

    row.innerHTML = `
        <div class="nv-admin-field">
            <label>Especificación</label>

            <input
                type="text"
                class="form-control"
                maxlength="100"
                data-variant-spec-key
                placeholder="Ej: magnification"
            >
        </div>

        <div class="nv-admin-field">
            <label>Valor</label>

            <input
                type="text"
                class="form-control"
                maxlength="500"
                data-variant-spec-value
                placeholder="Ej: 8x"
            >
        </div>

        <div class="nv-admin-spec-remove-wrap">
            <button
                type="button"
                class="nv-admin-spec-remove"
                data-variant-spec-remove
            >
                Eliminar
            </button>
        </div>
    `;

    return row;
};

const reindexVariantSpecifications = (list) => {
    const rows = Array.from(
        list.querySelectorAll(
            '[data-variant-spec-row]'
        )
    );

    rows.forEach((row, index) => {
        const keyInput = row.querySelector(
            '[data-variant-spec-key]'
        );

        const valueInput = row.querySelector(
            '[data-variant-spec-value]'
        );

        if (keyInput) {
            keyInput.name =
                `specifications[${index}][key]`;
        }

        if (valueInput) {
            valueInput.name =
                `specifications[${index}][value]`;
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', (event) => {
        const addButton = event.target.closest(
            '[data-variant-spec-add]'
        );

        if (addButton) {
            const list = document.querySelector(
                addButton.dataset.variantSpecAdd
            );

            if (!list) {
                return;
            }

            list.appendChild(
                createVariantSpecificationRow()
            );

            reindexVariantSpecifications(list);

            return;
        }

        const removeButton =
            event.target.closest(
                '[data-variant-spec-remove]'
            );

        if (!removeButton) {
            return;
        }

        const row = removeButton.closest(
            '[data-variant-spec-row]'
        );

        const list = removeButton.closest(
            '[data-variant-spec-list]'
        );

        if (!row || !list) {
            return;
        }

        row.remove();

        reindexVariantSpecifications(list);
    });
});