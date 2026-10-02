<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequestRequest;
use App\Models\ContactSetting;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\QuoteRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class QuoteRequestController extends Controller
{
    public function store(
        StoreQuoteRequestRequest $request,
        string $locale
    ): RedirectResponse {
        App::setLocale($locale);

        $validated = $request->validated();

        $product = Product::query()
            ->whereKey($validated['product_id'])
            ->where('status', Product::STATUS_PUBLISHED)
            ->firstOrFail();

        $variant = ProductVariant::query()
            ->whereKey($validated['product_variant_id'])
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->firstOrFail();

        $productName = $locale === 'en'
            ? ($product->name_en ?: $product->name_es)
            : $product->name_es;

        $variantName = $locale === 'en'
            ? ($variant->name_en ?: $variant->name_es)
            : $variant->name_es;

        $quote = QuoteRequest::create([
            'reference' => $this->generateReference(),
            'status' => QuoteRequest::STATUS_NEW,
            'locale' => $locale,

            'product_id' => $product->id,
            'product_variant_id' => $variant->id,

            'product_name_snapshot' => $productName,
            'variant_name_snapshot' => $variantName,
            'price_snapshot' => $variant->price,
            'currency' => $variant->currency,

            'quantity' => $validated['quantity'],

            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_message' => $validated['customer_message'] ?? null,

            'source_url' => url()->previous(),

            'utm_data' => $this->extractUtmData($validated),
        ]);

        $contactSettings = ContactSetting::query()->first();

        if (
            ! $contactSettings
            || ! $contactSettings->whatsapp_enabled
            || ! $contactSettings->whatsapp_number
        ) {
            return back()->with(
                'quote_success',
                $locale === 'en'
                    ? "Your quote request {$quote->reference} was registered successfully."
                    : "Tu solicitud de cotización {$quote->reference} fue registrada correctamente."
            );
        }

        $whatsappNumber = preg_replace(
            '/\D+/',
            '',
            $contactSettings->whatsapp_number
        );

        if (! $whatsappNumber) {
            return back()->with(
                'quote_success',
                $locale === 'en'
                    ? "Your quote request {$quote->reference} was registered successfully."
                    : "Tu solicitud de cotización {$quote->reference} fue registrada correctamente."
            );
        }

        $template = $locale === 'en'
            ? $contactSettings->quote_message_en
            : $contactSettings->quote_message_es;

        $template = $template ?: (
            $locale === 'en'
                ? 'Hello, I would like to request a quote for :product :variant.'
                : 'Hola, quiero cotizar el producto :product :variant.'
        );

        $message = str_replace(
            [
                ':product',
                ':variant',
            ],
            [
                $productName,
                $variantName,
            ],
            $template
        );

        $message .= "\n\n";

        $message .= $locale === 'en'
            ? "Reference: {$quote->reference}"
            : "Referencia: {$quote->reference}";

        $quote->update([
            'whatsapp_opened_at' => now(),
        ]);

        $whatsappUrl = sprintf(
            'https://wa.me/%s?text=%s',
            $whatsappNumber,
            rawurlencode($message)
        );

        return redirect()->away($whatsappUrl);
    }

    private function generateReference(): string
    {
        do {
            $reference = 'NVQ-'
                . now()->format('Ymd')
                . '-'
                . Str::upper(Str::random(8));
        } while (
            QuoteRequest::query()
                ->where('reference', $reference)
                ->exists()
        );

        return $reference;
    }

    private function extractUtmData(array $validated): ?array
    {
        $utm = array_filter(
            [
                'utm_source' => $validated['utm_source'] ?? null,
                'utm_medium' => $validated['utm_medium'] ?? null,
                'utm_campaign' => $validated['utm_campaign'] ?? null,
                'utm_term' => $validated['utm_term'] ?? null,
                'utm_content' => $validated['utm_content'] ?? null,
            ],
            static fn ($value) => $value !== null && $value !== ''
        );

        return $utm ?: null;
    }
}