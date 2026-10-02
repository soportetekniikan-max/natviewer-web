import initializeQuoteModal
    from './components/quote-modal.js';

import initializePublicNavigation
    from './components/public-navigation.js';

import initializeHomeExperience
    from './pages/home.js';

import initializeProductDetail
    from './pages/product-detail.js';

document.addEventListener(
    'DOMContentLoaded',
    () => {
        initializePublicNavigation();

        initializeHomeExperience();

        initializeQuoteModal();

        initializeProductDetail();
    }
);