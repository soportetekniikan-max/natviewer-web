<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactSettingRequest;
use App\Models\ContactSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactSettingController extends Controller
{
    public function edit(): View
    {
        return view(
            'admin.contact-settings.edit',
            [
                'settings' =>
                    $this->getSettings(),
            ]
        );
    }

    public function update(
        UpdateContactSettingRequest $request
    ): RedirectResponse {
        $settings =
            $this->getSettings();

        $validated =
            $request->validated();

        $settings->company_name =
            $validated['company_name'];

        $settings->whatsapp_number =
            $validated['whatsapp_number']
            ?? null;

        $settings->whatsapp_enabled =
            (bool)
            $validated['whatsapp_enabled'];

        $settings->email =
            $validated['email']
            ?? null;

        $settings->default_locale =
            $validated['default_locale'];

        $settings->default_currency =
            $validated['default_currency'];

        $settings->quote_message_es =
            $validated['quote_message_es']
            ?? null;

        $settings->quote_message_en =
            $validated['quote_message_en']
            ?? null;

        $settings->save();

        return redirect()
            ->route(
                'admin.contact-settings.edit'
            )
            ->with(
                'success',
                'Configuración comercial actualizada correctamente.'
            );
    }

    private function getSettings(): ContactSetting
    {
        return ContactSetting::query()
            ->firstOrCreate(
                [],
                [
                    'company_name' =>
                        'Natviewer',

                    'whatsapp_number' =>
                        null,

                    'whatsapp_enabled' =>
                        false,

                    'email' =>
                        null,

                    'default_locale' =>
                        'es',

                    'default_currency' =>
                        'COP',

                    'quote_message_es' =>
                        'Hola, estoy interesado en cotizar el producto :product :variant.',

                    'quote_message_en' =>
                        'Hello, I am interested in requesting a quote for :product :variant.',
                ]
            );
    }
}