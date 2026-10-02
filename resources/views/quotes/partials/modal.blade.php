<div
    class="modal fade nv-quote-modal"
    id="quoteModal"
    tabindex="-1"
    aria-labelledby="quoteModalLabel"
    aria-hidden="true"
    data-has-errors="{{ $errors->any() ? 'true' : 'false' }}"
    data-old-variant="{{ old('product_variant_id') }}"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form
                method="POST"
                action="{{ route(
                    'quotes.store',
                    [
                        'locale' => $locale,
                    ]
                ) }}"
                id="quoteForm"
            >
                @csrf

                <input
                    type="hidden"
                    name="product_id"
                    id="quoteProductId"
                    value="{{ old('product_id') }}"
                >

                <input
                    type="hidden"
                    name="product_variant_id"
                    id="quoteVariantId"
                    value="{{ old(
                        'product_variant_id'
                    ) }}"
                >

                <input
                    type="hidden"
                    name="utm_source"
                    value="{{ request()->query(
                        'utm_source'
                    ) }}"
                >

                <input
                    type="hidden"
                    name="utm_medium"
                    value="{{ request()->query(
                        'utm_medium'
                    ) }}"
                >

                <input
                    type="hidden"
                    name="utm_campaign"
                    value="{{ request()->query(
                        'utm_campaign'
                    ) }}"
                >

                <input
                    type="hidden"
                    name="utm_term"
                    value="{{ request()->query(
                        'utm_term'
                    ) }}"
                >

                <input
                    type="hidden"
                    name="utm_content"
                    value="{{ request()->query(
                        'utm_content'
                    ) }}"
                >

                <div class="modal-header">
                    <div>
                        <span class="nv-eyebrow">
                            {{
                                $locale === 'en'
                                    ? 'Request a quote'
                                    : 'Solicitar cotización'
                            }}
                        </span>

                        <h2
                            class="modal-title"
                            id="quoteModalLabel"
                        >
                            {{
                                $locale === 'en'
                                    ? 'Tell us how to contact you'
                                    : 'Cuéntanos cómo contactarte'
                            }}
                        </h2>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="{{
                            $locale === 'en'
                                ? 'Close'
                                : 'Cerrar'
                        }}"
                    ></button>
                </div>

                <div class="modal-body">
                    <div class="nv-quote-selection">
                        <span>
                            {{
                                $locale === 'en'
                                    ? 'Selected product'
                                    : 'Producto seleccionado'
                            }}
                        </span>

                        <strong id="quoteSelectedProduct">
                            Natviewer Falco
                        </strong>

                        <strong
                            id="quoteSelectedVariant"
                        ></strong>
                    </div>

                    <div class="nv-quote-fields">
                        <div class="nv-quote-field">
                            <label for="customerName">
                                {{
                                    $locale === 'en'
                                        ? 'Name *'
                                        : 'Nombre *'
                                }}
                            </label>

                            <input
                                type="text"
                                id="customerName"
                                name="customer_name"
                                value="{{ old(
                                    'customer_name'
                                ) }}"
                                maxlength="150"
                                autocomplete="name"
                                required
                                class="@error('customer_name') is-invalid @enderror"
                            >

                            @error('customer_name')
                                <p class="nv-quote-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="nv-quote-field">
                            <label for="customerPhone">
                                {{
                                    $locale === 'en'
                                        ? 'Phone *'
                                        : 'Teléfono *'
                                }}
                            </label>

                            <input
                                type="tel"
                                id="customerPhone"
                                name="customer_phone"
                                value="{{ old(
                                    'customer_phone'
                                ) }}"
                                maxlength="40"
                                autocomplete="tel"
                                required
                                class="@error('customer_phone') is-invalid @enderror"
                            >

                            @error('customer_phone')
                                <p class="nv-quote-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="nv-quote-field">
                            <label for="customerEmail">
                                {{
                                    $locale === 'en'
                                        ? 'Email'
                                        : 'Correo electrónico'
                                }}
                            </label>

                            <input
                                type="email"
                                id="customerEmail"
                                name="customer_email"
                                value="{{ old(
                                    'customer_email'
                                ) }}"
                                maxlength="255"
                                autocomplete="email"
                                class="@error('customer_email') is-invalid @enderror"
                            >

                            @error('customer_email')
                                <p class="nv-quote-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="nv-quote-field">
                            <label for="quoteQuantity">
                                {{
                                    $locale === 'en'
                                        ? 'Quantity'
                                        : 'Cantidad'
                                }}
                            </label>

                            <input
                                type="number"
                                id="quoteQuantity"
                                name="quantity"
                                min="1"
                                max="99"
                                value="{{ old(
                                    'quantity',
                                    1
                                ) }}"
                                required
                                class="@error('quantity') is-invalid @enderror"
                            >

                            @error('quantity')
                                <p class="nv-quote-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div
                            class="
                                nv-quote-field
                                nv-quote-field-full
                            "
                        >
                            <label for="customerMessage">
                                {{
                                    $locale === 'en'
                                        ? 'Message'
                                        : 'Mensaje'
                                }}
                            </label>

                            <textarea
                                id="customerMessage"
                                name="customer_message"
                                maxlength="2000"
                                placeholder="{{
                                    $locale === 'en'
                                        ? 'Tell us anything else you would like to know.'
                                        : 'Cuéntanos qué más te gustaría saber.'
                                }}"
                            >{{ old('customer_message') }}</textarea>

                            @error('customer_message')
                                <p class="nv-quote-error">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <p class="nv-quote-note">
                        {{
                            $locale === 'en'
                                ? 'Submitting this form registers your quote request. If WhatsApp is available, you will then continue there.'
                                : 'Al enviar este formulario registraremos tu solicitud. Si WhatsApp está disponible, luego continuarás allí.'
                        }}
                    </p>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="
                            nv-button
                            nv-button-outline
                        "
                        data-bs-dismiss="modal"
                    >
                        {{
                            $locale === 'en'
                                ? 'Cancel'
                                : 'Cancelar'
                        }}
                    </button>

                    <button
                        type="submit"
                        class="
                            nv-button
                            nv-button-primary
                        "
                        id="quoteSubmitButton"
                        data-loading-text="{{
                            $locale === 'en'
                                ? 'Sending...'
                                : 'Enviando...'
                        }}"
                    >
                        {{
                            $locale === 'en'
                                ? 'Request quote'
                                : 'Solicitar cotización'
                        }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>