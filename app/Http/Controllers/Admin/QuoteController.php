<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateQuoteRequest;
use App\Models\QuoteRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function index(
        Request $request
    ): View {
        $search = trim(
            (string) $request->query('q', '')
        );

        $status = trim(
            (string) $request->query(
                'status',
                ''
            )
        );

        $allowedStatuses = array_keys(
            $this->statusLabels()
        );

        $quotesQuery = QuoteRequest::query()
            ->orderByDesc('created_at');

        if (
            $status !== ''
            && in_array(
                $status,
                $allowedStatuses,
                true
            )
        ) {
            $quotesQuery->where(
                'status',
                $status
            );
        }

        if ($search !== '') {
            $quotesQuery->where(
                function ($query) use ($search) {
                    $query
                        ->where(
                            'reference',
                            'like',
                            '%'.$search.'%'
                        )
                        ->orWhere(
                            'customer_name',
                            'like',
                            '%'.$search.'%'
                        )
                        ->orWhere(
                            'customer_phone',
                            'like',
                            '%'.$search.'%'
                        )
                        ->orWhere(
                            'customer_email',
                            'like',
                            '%'.$search.'%'
                        );
                }
            );
        }

        $quotes = $quotesQuery
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total' =>
                QuoteRequest::query()->count(),

            'new' =>
                QuoteRequest::query()
                    ->where(
                        'status',
                        QuoteRequest::STATUS_NEW
                    )
                    ->count(),

            'contacted' =>
                QuoteRequest::query()
                    ->where(
                        'status',
                        QuoteRequest::STATUS_CONTACTED
                    )
                    ->count(),

            'won' =>
                QuoteRequest::query()
                    ->where(
                        'status',
                        QuoteRequest::STATUS_WON
                    )
                    ->count(),

            'lost' =>
                QuoteRequest::query()
                    ->where(
                        'status',
                        QuoteRequest::STATUS_LOST
                    )
                    ->count(),
        ];

        return view(
            'admin.quotes.index',
            [
                'quotes' => $quotes,
                'summary' => $summary,
                'statusLabels' =>
                    $this->statusLabels(),
                'currentStatus' => $status,
                'search' => $search,
            ]
        );
    }

    public function show(
        QuoteRequest $quote
    ): View {
        return view(
            'admin.quotes.show',
            [
                'quote' => $quote,
                'statusLabels' =>
                    $this->statusLabels(),
            ]
        );
    }

    public function update(
        UpdateQuoteRequest $request,
        QuoteRequest $quote
    ): RedirectResponse {
        $validated =
            $request->validated();

        $quote->status =
            $validated['status'];

        $quote->admin_notes =
            $validated['admin_notes']
            ?? null;

        $quote->save();

        return redirect()
            ->route(
                'admin.quotes.show',
                $quote
            )
            ->with(
                'success',
                'Cotización actualizada correctamente.'
            );
    }

    private function statusLabels(): array
    {
        return [
            QuoteRequest::STATUS_NEW =>
                'Nueva',

            QuoteRequest::STATUS_CONTACTED =>
                'Contactada',

            QuoteRequest::STATUS_WON =>
                'Ganada',

            QuoteRequest::STATUS_LOST =>
                'Perdida',

            QuoteRequest::STATUS_CANCELLED =>
                'Cancelada',
        ];
    }
}