<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $spanishHomeUrl = route(
            'home',
            [
                'locale' => 'es',
            ]
        );

        $englishHomeUrl = route(
            'home',
            [
                'locale' => 'en',
            ]
        );

        $sitemapEntries = $this->localizedEntries(
            $spanishHomeUrl,
            $englishHomeUrl
        );

        $products = Product::query()
            ->select([
                'id',
                'slug',
            ])
            ->where(
                'status',
                Product::STATUS_PUBLISHED
            )
            ->whereHas(
                'category',
                function ($query) {
                    $query->where(
                        'is_active',
                        true
                    );
                }
            )
            ->whereHas(
                'brand',
                function ($query) {
                    $query->where(
                        'is_active',
                        true
                    );
                }
            )
            ->orderBy('id')
            ->get();

        foreach ($products as $product) {
            $spanishProductUrl = route(
                'products.show.es',
                [
                    'product' => $product->slug,
                ]
            );

            $englishProductUrl = route(
                'products.show.en',
                [
                    'product' => $product->slug,
                ]
            );

            foreach (
                $this->localizedEntries(
                    $spanishProductUrl,
                    $englishProductUrl
                ) as $entry
            ) {
                $sitemapEntries[] = $entry;
            }
        }

        return response()
            ->view(
                'seo.sitemap',
                [
                    'sitemapEntries' =>
                        $sitemapEntries,
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

    private function localizedEntries(
        string $spanishUrl,
        string $englishUrl
    ): array {
        $alternates = [
            'es' => $spanishUrl,
            'en' => $englishUrl,
            'x-default' => $spanishUrl,
        ];

        return [
            [
                'loc' => $spanishUrl,
                'alternates' => $alternates,
            ],
            [
                'loc' => $englishUrl,
                'alternates' => $alternates,
            ],
        ];
    }
}