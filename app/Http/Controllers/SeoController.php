<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        return response()
            ->view(
                'seo.sitemap',
                [
                    'spanishUrl' => route(
                        'home',
                        [
                            'locale' => 'es',
                        ]
                    ),

                    'englishUrl' => route(
                        'home',
                        [
                            'locale' => 'en',
                        ]
                    ),
                ]
            )
            ->header(
                'Content-Type',
                'application/xml; charset=UTF-8'
            );
    }

    public function robots(): Response
    {
        $content = implode(
            PHP_EOL,
            [
                'User-agent: *',
                'Allow: /',
                'Disallow: /admin',
                '',
                'Sitemap: '.route('sitemap'),
                '',
            ]
        );

        return response(
            $content,
            200,
            [
                'Content-Type' =>
                    'text/plain; charset=UTF-8',
            ]
        );
    }
}