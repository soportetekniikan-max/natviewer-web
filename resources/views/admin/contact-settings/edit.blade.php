@extends('admin.layout')

@section('title', 'Configuración de contacto')

@section('content')
    <div class="container-fluid nv-admin-dashboard">
        <div class="nv-admin-page-header">
            <div>
                <span class="nv-eyebrow">
                    Configuración
                </span>

                <h1>
                    Contacto y configuración comercial
                </h1>

                <p>
                    Administra los datos de contacto,
                    WhatsApp y valores predeterminados
                    utilizados por Natviewer.
                </p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>
                    Revisa los siguientes campos:
                </strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route(
                'admin.contact-settings.update'
            ) }}"
            class="nv-admin-product-form"
        >
            @csrf
            @method('PUT')

            {{-- INFORMACIÓN GENERAL --}}
            <section class="nv-admin-form-card mb-4">
                <div class="nv-admin-form-card-header">
                    <div>
                        <h2>
                            Información comercial
                        </h2>

                        <p>
                            Datos principales de contacto
                            de la empresa.
                        </p>
                    </div>
                </div>

                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label for="company_name">
                            Nombre de la empresa *
                        </label>

                        <input
                            type="text"
                            id="company_name"
                            name="company_name"
                            class="form-control"
                            maxlength="255"
                            value="{{ old(
                                'company_name',
                                $settings->company_name
                            ) }}"
                            required
                        >
                    </div>

                    <div class="nv-admin-field">
                        <label for="email">
                            Email comercial
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            maxlength="255"
                            value="{{ old(
                                'email',
                                $settings->email
                            ) }}"
                            placeholder="ventas@natviewer.com"
                        >
                    </div>
                </div>
            </section>

            {{-- WHATSAPP --}}
            <section class="nv-admin-form-card mb-4">
                <div class="nv-admin-form-card-header">
                    <div>
                        <h2>
                            WhatsApp
                        </h2>

                        <p>
                            Configura el canal utilizado
                            después de una solicitud
                            de cotización.
                        </p>
                    </div>
                </div>

                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label for="whatsapp_number">
                            Número de WhatsApp
                        </label>

                        <input
                            type="text"
                            id="whatsapp_number"
                            name="whatsapp_number"
                            class="form-control"
                            maxlength="40"
                            value="{{ old(
                                'whatsapp_number',
                                $settings->whatsapp_number
                            ) }}"
                            placeholder="+57 300 000 0000"
                        >

                        <small>
                            Incluye código de país.
                        </small>
                    </div>

                    <div class="nv-admin-field">
                        <input
                            type="hidden"
                            name="whatsapp_enabled"
                            value="0"
                        >

                        <label class="nv-admin-toggle">
                            <input
                                type="checkbox"
                                name="whatsapp_enabled"
                                value="1"
                                @checked(
                                    old(
                                        'whatsapp_enabled',
                                        $settings->whatsapp_enabled
                                    )
                                )
                            >

                            <span>
                                Activar WhatsApp
                            </span>
                        </label>

                        <small>
                            Si está desactivado, la
                            cotización se guardará sin
                            redirigir a WhatsApp.
                        </small>
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label for="quote_message_es">
                            Mensaje de cotización ES
                        </label>

                        <textarea
                            id="quote_message_es"
                            name="quote_message_es"
                            class="form-control"
                            rows="6"
                            maxlength="2000"
                            placeholder="Hola, quiero más información sobre..."
                        >{{ old(
                            'quote_message_es',
                            $settings->quote_message_es
                        ) }}</textarea>

                        <small>
                            Mensaje base utilizado para
                            solicitudes en español.
                        </small>
                    </div>

                    <div class="nv-admin-field nv-admin-field-full">
                        <label for="quote_message_en">
                            Mensaje de cotización EN
                        </label>

                        <textarea
                            id="quote_message_en"
                            name="quote_message_en"
                            class="form-control"
                            rows="6"
                            maxlength="2000"
                            placeholder="Hello, I would like more information about..."
                        >{{ old(
                            'quote_message_en',
                            $settings->quote_message_en
                        ) }}</textarea>

                        <small>
                            Mensaje base utilizado para
                            solicitudes en inglés.
                        </small>
                    </div>
                </div>
            </section>

            {{-- CONFIGURACIÓN REGIONAL --}}
            <section class="nv-admin-form-card">
                <div class="nv-admin-form-card-header">
                    <div>
                        <h2>
                            Configuración regional
                        </h2>

                        <p>
                            Valores predeterminados utilizados
                            por el sitio.
                        </p>
                    </div>
                </div>

                <div class="nv-admin-form-grid">
                    <div class="nv-admin-field">
                        <label for="default_locale">
                            Idioma predeterminado
                        </label>

                        <select
                            id="default_locale"
                            name="default_locale"
                            class="form-select"
                            required
                        >
                            <option
                                value="es"
                                @selected(
                                    old(
                                        'default_locale',
                                        $settings->default_locale
                                    ) === 'es'
                                )
                            >
                                Español
                            </option>

                            <option
                                value="en"
                                @selected(
                                    old(
                                        'default_locale',
                                        $settings->default_locale
                                    ) === 'en'
                                )
                            >
                                English
                            </option>
                        </select>
                    </div>

                    <div class="nv-admin-field">
                        <label for="default_currency">
                            Moneda predeterminada
                        </label>

                        <input
                            type="text"
                            id="default_currency"
                            name="default_currency"
                            class="form-control text-uppercase"
                            minlength="3"
                            maxlength="3"
                            value="{{ old(
                                'default_currency',
                                $settings->default_currency
                            ) }}"
                            placeholder="COP"
                            required
                        >

                        <small>
                            Código ISO de tres letras,
                            por ejemplo COP o USD.
                        </small>
                    </div>
                </div>
            </section>

            <div class="nv-admin-form-actions">
                <button
                    type="submit"
                    class="nv-button nv-button-primary"
                >
                    Guardar configuración
                </button>
            </div>
        </form>
    </div>
@endsection