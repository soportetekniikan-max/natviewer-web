<?php

namespace App\Http\Controllers;

use App\Models\ContactSetting;
use App\Models\Product;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(string $locale): View
    {
        App::setLocale($locale);

        $products = Product::query()
            ->with([
                'category',
                'brand',
                'primaryImage',
                'variants' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('id');
                },
            ])
            ->where('status', Product::STATUS_PUBLISHED)
            ->whereHas('category', function ($query) {
                $query->where('is_active', true);
            })
            ->whereHas('brand', function ($query) {
                $query->where('is_active', true);
            })
            ->orderByDesc('is_featured')
            ->orderBy('id')
            ->get();

        $contactSettings = ContactSetting::query()->first();

        return view('home', [
            'locale' => $locale,
            'products' => $products,
            'contactSettings' => $contactSettings,
        ]);
    }
}