import initializeQuoteModal
    from './components/quote-modal.js';

import initializeProductDetail
    from './pages/product-detail.js';

document.addEventListener(
    'DOMContentLoaded',
    () => {
        initializeQuoteModal();
        initializeProductDetail();
    }
);