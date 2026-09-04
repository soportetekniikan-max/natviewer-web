<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\QuoteRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'products' => Product::query()->count(),

            'variants' => ProductVariant::query()
                ->where('is_active', true)
                ->count(),

            'new_quotes' => QuoteRequest::query()
                ->where(
                    'status',
                    QuoteRequest::STATUS_NEW
                )
                ->count(),

            'quotes_total' => QuoteRequest::query()->count(),
        ];

        $latestQuotes = QuoteRequest::query()
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'latestQuotes' => $latestQuotes,
        ]);
    }
}