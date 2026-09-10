import { Modal } from 'bootstrap';

const initializeQuoteModal = () => {
    const modalElement =
        document.getElementById('quoteModal');

    if (!modalElement) {
        return;
    }

    const form =
        document.getElementById('quoteForm');

    const productInput =
        document.getElementById(
            'quoteProductId'
        );

    const variantInput =
        document.getElementById(
            'quoteVariantId'
        );

    const selectedProduct =
        document.getElementById(
            'quoteSelectedProduct'
        );

    const selectedVariant =
        document.getElementById(
            'quoteSelectedVariant'
        );

    const submitButton =
        document.getElementById(
            'quoteSubmitButton'
        );

    const triggers = Array.from(
        document.querySelectorAll(
            '.nv-quote-trigger'
        )
    );

    const populateModal = (trigger) => {
        if (!trigger) {
            return;
        }

        if (productInput) {
            productInput.value =
                trigger.dataset.quoteProduct
                || '';
        }

        if (variantInput) {
            variantInput.value =
                trigger.dataset.quoteVariant
                || '';
        }

        if (selectedProduct) {
            selectedProduct.textContent =
                trigger.dataset.quoteProductName
                || '';
        }

        if (selectedVariant) {
            selectedVariant.textContent =
                trigger.dataset.quoteVariantName
                || '';
        }
    };

    modalElement.addEventListener(
        'show.bs.modal',
        (event) => {
            if (event.relatedTarget) {
                populateModal(
                    event.relatedTarget
                );
            }
        }
    );

    const hasValidationErrors =
        modalElement.dataset.hasErrors
        === 'true';

    const oldVariantId =
        modalElement.dataset.oldVariant
        || '';

    if (hasValidationErrors) {
        const previousTrigger =
            triggers.find(
                (trigger) =>
                    trigger.dataset.quoteVariant
                    === oldVariantId
            );

        if (previousTrigger) {
            populateModal(
                previousTrigger
            );
        }

        Modal
            .getOrCreateInstance(
                modalElement
            )
            .show();
    }

    if (form && submitButton) {
        form.addEventListener(
            'submit',
            () => {
                submitButton.disabled = true;

                const loadingText =
                    submitButton.dataset.loadingText;

                if (loadingText) {
                    submitButton.textContent =
                        loadingText;
                }
            }
        );
    }
};

export default initializeQuoteModal;