<?php

namespace App\Http\Controllers;

use App\Models\ContactSetting;
use Illuminate\Http\RedirectResponse;

class DefaultLocaleController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $locale = ContactSetting::query()
            ->value('default_locale');

        if (
            ! in_array(
                $locale,
                ['es', 'en'],
                true
            )
        ) {
            $locale = 'en';
        }

        return redirect()->route(
            'home',
            [
                'locale' => $locale,
            ]
        );
    }
}