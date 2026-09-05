import { Modal } from 'bootstrap';

import './admin-products';
import './admin-variants';

document.addEventListener('DOMContentLoaded', () => {
    const quoteModalElement =
        document.getElementById('quoteModal');

    if (!quoteModalElement) {
        return;
    }

    const quoteForm =
        document.getElementById('quoteForm');

    const productInput =
        document.getElementById('quoteProductId');

    const variantInput =
        document.getElementById('quoteVariantId');

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

        productInput.value =
            trigger.dataset.quoteProduct
            || '';

        variantInput.value =
            trigger.dataset.quoteVariant
            || '';

        selectedProduct.textContent =
            trigger.dataset.quoteProductName
            || '';

        selectedVariant.textContent =
            trigger.dataset.quoteVariantName
            || '';
    };

    quoteModalElement.addEventListener(
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
        quoteModalElement.dataset.hasErrors
        === 'true';

    const oldVariantId =
        quoteModalElement.dataset.oldVariant
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
                quoteModalElement
            )
            .show();
    }

    if (quoteForm && submitButton) {
        quoteForm.addEventListener(
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
});