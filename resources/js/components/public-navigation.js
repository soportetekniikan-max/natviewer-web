export default function initializePublicNavigation() {
    const header =
        document.querySelector(
            '.nv-site-header'
        );

    const navbar =
        document.querySelector(
            '.nv-navbar'
        );

    const toggle =
        document.querySelector(
            '[data-public-nav-toggle]'
        );

    const menu =
        document.querySelector(
            '[data-public-nav-menu]'
        );

    /*
     * Estado visual del header.
     */
    if (header) {
        let scrollFrame = null;

        const updateHeaderState = () => {
            header.classList.toggle(
                'is-scrolled',
                window.scrollY > 24
            );

            scrollFrame = null;
        };

        const requestHeaderUpdate = () => {
            if (scrollFrame !== null) {
                return;
            }

            scrollFrame =
                window.requestAnimationFrame(
                    updateHeaderState
                );
        };

        updateHeaderState();

        window.addEventListener(
            'scroll',
            requestHeaderUpdate,
            {
                passive: true,
            }
        );
    }


    /*
     * Reflejo dinámico del cristal.
     *
     * Solo se activa cuando existe mouse preciso
     * y el usuario no solicita reducción de
     * movimiento.
     */
    if (navbar) {
        const canUsePointerEffect =
            window.matchMedia(
                '(pointer: fine)'
            ).matches;

        const prefersReducedMotion =
            window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;

        if (
            canUsePointerEffect
            && !prefersReducedMotion
        ) {
            let pointerFrame = null;

            let pointerX = 50;
            let pointerY = 0;

            const updateGlassReflection = () => {
                navbar.style.setProperty(
                    '--nv-glass-x',
                    `${pointerX}%`
                );

                navbar.style.setProperty(
                    '--nv-glass-y',
                    `${pointerY}%`
                );

                pointerFrame = null;
            };

            navbar.addEventListener(
                'pointermove',
                (event) => {
                    const rect =
                        navbar.getBoundingClientRect();

                    if (
                        rect.width <= 0
                        || rect.height <= 0
                    ) {
                        return;
                    }

                    pointerX =
                        (
                            (
                                event.clientX
                                - rect.left
                            )
                            / rect.width
                        )
                        * 100;

                    pointerY =
                        (
                            (
                                event.clientY
                                - rect.top
                            )
                            / rect.height
                        )
                        * 100;

                    if (
                        pointerFrame !== null
                    ) {
                        return;
                    }

                    pointerFrame =
                        window.requestAnimationFrame(
                            updateGlassReflection
                        );
                }
            );

            navbar.addEventListener(
                'pointerleave',
                () => {
                    pointerX = 50;
                    pointerY = 0;

                    if (
                        pointerFrame !== null
                    ) {
                        return;
                    }

                    pointerFrame =
                        window.requestAnimationFrame(
                            updateGlassReflection
                        );
                }
            );
        }
    }


    /*
     * No hay navegación móvil en esta vista.
     * Los efectos anteriores pueden seguir
     * funcionando igualmente.
     */
    if (!toggle || !menu) {
        return;
    }


    /* =====================================================
       MOBILE NAVIGATION
       ===================================================== */

    const close = (
        restoreFocus = false
    ) => {
        toggle.setAttribute(
            'aria-expanded',
            'false'
        );

        menu.hidden = true;

        if (restoreFocus) {
            toggle.focus();
        }
    };

    const open = () => {
        toggle.setAttribute(
            'aria-expanded',
            'true'
        );

        menu.hidden = false;
    };

    toggle.addEventListener(
        'click',
        () => {
            const expanded =
                toggle.getAttribute(
                    'aria-expanded'
                )
                === 'true';

            if (expanded) {
                close();

                return;
            }

            open();
        }
    );


    /*
     * Cerrar al seleccionar navegación.
     */
    menu.querySelectorAll('a')
        .forEach(
            (link) => {
                link.addEventListener(
                    'click',
                    () => {
                        close();
                    }
                );
            }
        );


    /*
     * Escape cierra y devuelve foco.
     */
    document.addEventListener(
        'keydown',
        (event) => {
            if (
                event.key !== 'Escape'
            ) {
                return;
            }

            if (
                toggle.getAttribute(
                    'aria-expanded'
                )
                === 'true'
            ) {
                close(true);
            }
        }
    );


    /*
     * Clic fuera del menú.
     */
    document.addEventListener(
        'pointerdown',
        (event) => {
            if (
                toggle.getAttribute(
                    'aria-expanded'
                )
                !== 'true'
            ) {
                return;
            }

            if (
                menu.contains(
                    event.target
                )
                || toggle.contains(
                    event.target
                )
            ) {
                return;
            }

            close();
        }
    );


    /*
     * Cuando volvemos a desktop dejamos
     * limpio el estado móvil.
     */
    window.addEventListener(
        'resize',
        () => {
            if (
                window.innerWidth >= 992
            ) {
                close();
            }
        },
        {
            passive: true,
        }
    );
}