<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class PrivacyPolicyController extends Controller
{
    public function __invoke(
        string $locale
    ): View {
        abort_unless(
            in_array(
                $locale,
                ['es', 'en'],
                true
            ),
            404
        );

        App::setLocale(
            $locale
        );

        return view(
            'legal.privacy-policy',
            [
                'locale' => $locale,
            ]
        );
    }
}