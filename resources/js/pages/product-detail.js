const initializeProductDetail = () => {
    const page = document.querySelector(
        '[data-product-detail]'
    );

    if (!page) {
        return;
    }

    const mainImage = page.querySelector(
        '[data-main-product-image]'
    );

    const galleryButtons = Array.from(
        page.querySelectorAll(
            '[data-gallery-image]'
        )
    );

    galleryButtons.forEach((button) => {
        button.addEventListener(
            'click',
            () => {
                if (!mainImage) {
                    return;
                }

                mainImage.src =
                    button.dataset.imageUrl
                    || '';

                mainImage.alt =
                    button.dataset.imageAlt
                    || '';

                galleryButtons.forEach(
                    (galleryButton) => {
                        galleryButton.classList
                            .remove('is-active');

                        galleryButton.setAttribute(
                            'aria-pressed',
                            'false'
                        );
                    }
                );

                button.classList.add(
                    'is-active'
                );

                button.setAttribute(
                    'aria-pressed',
                    'true'
                );
            }
        );
    });

    const variantCards = Array.from(
        page.querySelectorAll(
            '[data-variant-card]'
        )
    );

    const variantSelect = page.querySelector(
        '[data-product-variant-select]'
    );

    const selectedPrice = page.querySelector(
        '[data-selected-price]'
    );

    const selectedStock = page.querySelector(
        '[data-selected-stock]'
    );

    const selectVariant = (variantId) => {
        const selectedCard =
            variantCards.find(
                (card) =>
                    card.dataset.variantId
                    === String(variantId)
            );

        if (!selectedCard) {
            return;
        }

        variantCards.forEach((card) => {
            card.classList.remove(
                'is-selected'
            );

            const radio =
                card.querySelector(
                    '[data-variant-radio]'
                );

            if (radio) {
                radio.checked = false;
            }
        });

        selectedCard.classList.add(
            'is-selected'
        );

        const selectedRadio =
            selectedCard.querySelector(
                '[data-variant-radio]'
            );

        if (selectedRadio) {
            selectedRadio.checked = true;
        }

        if (variantSelect) {
            variantSelect.value =
                String(variantId);
        }

        if (selectedPrice) {
            selectedPrice.textContent =
                selectedCard.dataset
                    .variantPrice
                || '';
        }

        if (selectedStock) {
            selectedStock.textContent =
                selectedCard.dataset
                    .variantStock
                || '';
        }
    };

    variantCards.forEach((card) => {
        const radio = card.querySelector(
            '[data-variant-radio]'
        );

        if (!radio) {
            return;
        }

        radio.addEventListener(
            'change',
            () => {
                if (radio.checked) {
                    selectVariant(
                        card.dataset.variantId
                    );
                }
            }
        );
    });

    if (variantSelect) {
        variantSelect.addEventListener(
            'change',
            () => {
                selectVariant(
                    variantSelect.value
                );
            }
        );
    }

    page
        .querySelectorAll(
            '[data-quote-variant]'
        )
        .forEach((button) => {
            button.addEventListener(
                'click',
                () => {
                    const variantId =
                        button.dataset
                            .quoteVariant;

                    if (!variantId) {
                        return;
                    }

                    selectVariant(
                        variantId
                    );
                }
            );
        });
};

export default initializeProductDetail;