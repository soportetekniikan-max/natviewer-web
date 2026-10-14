export default function initializeHeroCarousel(home) {
    const carousel = home.querySelector('[data-hero-carousel]');
    if (!carousel) {
        return;
    }

    let slides;
    try {
        slides = JSON.parse(carousel.dataset.heroCarousel);
    } catch {
        return;
    }

    const image = carousel.querySelector('.nv-hero-product-image');
    const controls = carousel.querySelector('[data-carousel-controls]');
    if (!image || !controls || !Array.isArray(slides) || slides.length < 2) {
        return;
    }

    const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const dots = Array.from(controls.querySelectorAll('[data-carousel-slide]'));
    let index = 0;
    let timer;
    let hovered = false;
    let focused = false;
    let visible = false;
    let requestId = 0;
    let loading = false;

    const schedule = () => {
        window.clearTimeout(timer);
        if (!hovered && !focused && visible && !document.hidden && !motion.matches && !loading) {
            timer = window.setTimeout(() => show(index + 1), 6000);
        }
    };

    const show = async (nextIndex) => {
        const currentRequest = ++requestId;
        window.clearTimeout(timer);
        loading = true;
        const next = (nextIndex + slides.length) % slides.length;
        const slide = slides[next];
        const preload = new Image();
        preload.decoding = 'async';
        preload.sizes = image.sizes;
        preload.srcset = slide.srcset || '';
        preload.src = slide.url;

        try {
            await preload.decode();
            if (currentRequest !== requestId) {
                return;
            }
            image.srcset = slide.srcset || '';
            image.src = slide.url;
            image.alt = slide.alt;
            image.fetchPriority = 'auto';
            index = next;
            dots.forEach((dot, slideIndex) => dot.setAttribute('aria-pressed', String(slideIndex === index)));
            if (!motion.matches) {
                image.getAnimations().forEach((animation) => animation.cancel());
                image.animate([{ opacity: 0.2 }, { opacity: 1 }], { duration: 420, easing: 'ease-out' });
            }
        } catch {
            // Keep the current photo visible when another photo cannot load.
        } finally {
            if (currentRequest === requestId) {
                loading = false;
                schedule();
            }
        }
    };

    controls.hidden = false;
    carousel.classList.add('has-carousel');
    dots.forEach((dot) => dot.addEventListener('click', () => show(Number(dot.dataset.carouselSlide))));
    carousel.addEventListener('pointerenter', (event) => {
        hovered = event.pointerType === 'mouse';
        schedule();
    });
    carousel.addEventListener('pointerleave', () => { hovered = false; schedule(); });
    carousel.addEventListener('focusin', (event) => {
        focused = event.target.matches(':focus-visible');
        schedule();
    });
    carousel.addEventListener('focusout', (event) => {
        const next = event.relatedTarget;
        focused = next instanceof Element && carousel.contains(next) && next.matches(':focus-visible');
        schedule();
    });
    document.addEventListener('visibilitychange', schedule);
    motion.addEventListener('change', schedule);

    if ('IntersectionObserver' in window) {
        new IntersectionObserver(([entry]) => {
            visible = entry.isIntersecting;
            schedule();
        }, { threshold: 0.25 }).observe(carousel);
    } else {
        visible = true;
        schedule();
    }
}
