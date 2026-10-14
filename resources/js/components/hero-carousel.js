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
    const toggle = controls.querySelector('[data-carousel-toggle]');
    const icon = toggle.querySelector('[data-carousel-toggle-icon]');
    const counter = controls.querySelector('[data-carousel-count]');
    let index = 0;
    let timer;
    let paused = false;
    let hovered = false;
    let focused = false;
    let visible = false;
    let requestId = 0;
    let loading = false;

    const schedule = () => {
        window.clearTimeout(timer);
        if (!paused && !hovered && !focused && visible && !document.hidden && !motion.matches && !loading) {
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
            counter.textContent = `${index + 1} / ${slides.length}`;
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
    toggle.hidden = motion.matches;
    controls.querySelector('[data-carousel-previous]').addEventListener('click', () => show(index - 1));
    controls.querySelector('[data-carousel-next]').addEventListener('click', () => show(index + 1));
    toggle.addEventListener('click', () => {
        paused = !paused;
        toggle.setAttribute('aria-label', paused ? toggle.dataset.playLabel : toggle.dataset.pauseLabel);
        icon.textContent = paused ? '▶' : 'Ⅱ';
        schedule();
    });
    carousel.addEventListener('mouseenter', () => { hovered = true; schedule(); });
    carousel.addEventListener('mouseleave', () => { hovered = false; schedule(); });
    carousel.addEventListener('focusin', () => { focused = true; schedule(); });
    carousel.addEventListener('focusout', (event) => {
        focused = carousel.contains(event.relatedTarget);
        schedule();
    });
    document.addEventListener('visibilitychange', schedule);
    motion.addEventListener('change', () => {
        toggle.hidden = motion.matches;
        schedule();
    });

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
