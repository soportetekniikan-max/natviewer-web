export default function initializeHomeExperience() {
    const home =
        document.querySelector(
            '.nv-home-page'
        );

    if (!home) {
        return;
    }

    const body =
        document.body;

    const prefersReducedMotion =
        window.matchMedia(
            '(prefers-reduced-motion: reduce)'
        ).matches;


    /* =====================================================
       REDUCED MOTION
       ===================================================== */

    if (prefersReducedMotion) {
        return;
    }


    /* =====================================================
       REVEAL ON SCROLL
       ===================================================== */

    body.classList.add(
        'nv-motion-ready'
    );

    const revealSelectors = [
        '.nv-section-heading',
        '.nv-strip-item',
        '.nv-product-card',
        '.nv-benefit-card',
        '.nv-info-card',
        '.nv-footer-grid',
    ];

    const revealElements =
        Array.from(
            document.querySelectorAll(
                revealSelectors.join(',')
            )
        );

    revealElements.forEach(
        (element, index) => {
            element.classList.add(
                'nv-reveal'
            );

            /*
             * Stagger pequeño.
             * Nunca supera 180 ms.
             */
            const delay =
                (index % 4) * 60;

            element.style.setProperty(
                '--nv-reveal-delay',
                `${delay}ms`
            );
        }
    );


    if (
        'IntersectionObserver'
        in window
    ) {
        const observer =
            new IntersectionObserver(
                (entries) => {
                    entries.forEach(
                        (entry) => {
                            if (
                                !entry.isIntersecting
                            ) {
                                return;
                            }

                            entry.target.classList.add(
                                'is-visible'
                            );

                            observer.unobserve(
                                entry.target
                            );
                        }
                    );
                },
                {
                    threshold: 0.12,

                    rootMargin:
                        '0px 0px -7% 0px',
                }
            );

        revealElements.forEach(
            (element) => {
                observer.observe(
                    element
                );
            }
        );
    } else {
        revealElements.forEach(
            (element) => {
                element.classList.add(
                    'is-visible'
                );
            }
        );
    }


    /* =====================================================
       HERO PARALLAX
       ===================================================== */

    const hero =
        home.querySelector(
            '.nv-home-hero'
        );

    if (!hero) {
        return;
    }

    let scrollFrame = null;

    const updateHero = () => {
        const rect =
            hero.getBoundingClientRect();

        const heroHeight =
            Math.max(
                hero.offsetHeight,
                1
            );

        const progress =
            Math.min(
                Math.max(
                    -rect.top
                    / heroHeight,
                    0
                ),
                1
            );

        /*
         * Movimiento intencionalmente muy
         * pequeño para evitar efecto "parallax
         * barato".
         */
        const shift =
            progress * 22;

        hero.style.setProperty(
            '--nv-hero-shift',
            `${shift}px`
        );

        scrollFrame = null;
    };

    const requestHeroUpdate = () => {
        if (
            scrollFrame !== null
        ) {
            return;
        }

        scrollFrame =
            window.requestAnimationFrame(
                updateHero
            );
    };

    updateHero();

    window.addEventListener(
        'scroll',
        requestHeroUpdate,
        {
            passive: true,
        }
    );

    window.addEventListener(
        'resize',
        requestHeroUpdate,
        {
            passive: true,
        }
    );
}