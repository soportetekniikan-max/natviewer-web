<footer
    class="nv-site-footer"
    id="contact"
>
    <div class="container">
        <div class="nv-footer-grid">
            <div>
                <img
                    src="{{ asset(
                        'images/logo-natviewer-white.png'
                    ) }}"
                    alt="Natviewer"
                    class="nv-footer-logo"
                >

                <p>
                    {{ __('public.footer.text') }}
                </p>
            </div>

            <div class="nv-footer-card">
                <span>
                    {{ __('public.footer.kicker') }}
                </span>

                <h2>
                    {{ __('public.footer.contact_title') }}
                </h2>

                <p>
                    {{ __('public.footer.contact_text') }}
                </p>

                <a
                    href="{{ $navigationUrls['products'] }}"
                    class="
                        nv-button
                        nv-button-primary
                    "
                >
                    {{ __('public.footer.quote_button') }}
                </a>
            </div>
        </div>

        <div class="nv-footer-bottom">
            <span>
                © {{ date('Y') }} Natviewer.
                {{ __('public.footer.rights') }}
            </span>

            <span>
                {{ __('public.footer.version') }}
            </span>
        </div>
    </div>
</footer>