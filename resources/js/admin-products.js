const reindexSpecificationRows = (list) => {
    const variantId = list.dataset.variantId;

    if (!variantId) {
        return;
    }

    const rows = Array.from(
        list.querySelectorAll('[data-spec-row]')
    );

    rows.forEach((row, index) => {
        const keyInput = row.querySelector(
            '[data-spec-key]'
        );

        const valueInput = row.querySelector(
            '[data-spec-value]'
        );

        if (keyInput) {
            keyInput.name =
                `variants[${variantId}]` +
                `[specifications][${index}][key]`;
        }

        if (valueInput) {
            valueInput.name =
                `variants[${variantId}]` +
                `[specifications][${index}][value]`;
        }
    });
};

const createSpecificationRow = () => {
    const row = document.createElement('div');

    row.className = 'nv-admin-spec-row';
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

document.addEventListener('DOMContentLoaded', () => {
    document
        .querySelectorAll('[data-add-spec]')
        .forEach((button) => {
            button.addEventListener('click', () => {
                const variantId =
                    button.dataset.variantId;

                const list = document.querySelector(
                    `[data-spec-list="${variantId}"]`
                );

                if (!list) {
                    return;
                }

                list.appendChild(
                    createSpecificationRow()
                );

                reindexSpecificationRows(list);
            });
        });

    document.addEventListener(
        'click',
        (event) => {
            const button = event.target.closest(
                '[data-remove-spec]'
            );

            if (!button) {
                return;
            }

            const row = button.closest(
                '[data-spec-row]'
            );

            const list = button.closest(
                '[data-spec-list]'
            );

            if (!row || !list) {
                return;
            }

            row.remove();

            reindexSpecificationRows(list);
        }
    );
});