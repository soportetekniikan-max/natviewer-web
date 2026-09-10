<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductDetail\ProductDetailBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class ProductDetailController extends Controller
{
    public function show(
        Product $product,
        Request $request,
        ProductDetailBuilder $builder
    ): View {
        $locale = (string) $request->route(
            'locale',
            'es'
        );

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
            'products.show',
            $builder->build(
                $product,
                $request,
                $locale
            )
        );
    }
}