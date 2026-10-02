<?php

namespace App\Http\Controllers;

use App\Services\Home\HomePageBuilder;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(
        string $locale,
        HomePageBuilder $builder
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
            'home',
            $builder->build(
                $locale
            )
        );
    }
}