export default function initializePublicNavigation() {
    const toggle =
        document.querySelector(
            '[data-public-nav-toggle]'
        );

    const menu =
        document.querySelector(
            '[data-public-nav-menu]'
        );

    if (!toggle || !menu) {
        return;
    }

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
                ) === 'true';

            if (expanded) {
                close();
                return;
            }

            open();
        }
    );

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

    document.addEventListener(
        'keydown',
        (event) => {
            if (event.key !== 'Escape') {
                return;
            }

            if (
                toggle.getAttribute(
                    'aria-expanded'
                ) === 'true'
            ) {
                close(true);
            }
        }
    );

    window.addEventListener(
        'resize',
        () => {
            if (
                window.innerWidth >= 992
            ) {
                close();
            }
        }
    );
}