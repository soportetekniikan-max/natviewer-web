<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        $legacyProduct = DB::table('products')
            ->where(
                'slug',
                'natviewer-falco'
            )
            ->first();

        /*
         * En instalaciones nuevas esta migración
         * se ejecutará antes del ProductSeeder.
         *
         * Si el producto legacy no existe,
         * no hay datos que transformar.
         */
        if (! $legacyProduct) {
            return;
        }

        $variant8 = DB::table(
            'product_variants'
        )
            ->where(
                'sku',
                'NV-FALCO-8X42-UD'
            )
            ->first();

        $variant10 = DB::table(
            'product_variants'
        )
            ->where(
                'sku',
                'NV-FALCO-10X42-UD'
            )
            ->first();

        if (! $variant8 || ! $variant10) {
            throw new \RuntimeException(
                'No se encontraron las variantes Falco 8×42 UD y 10×42 UD necesarias para separar los productos.'
            );
        }

        $now = now();

        $copiedFiles = [];

        try {
            DB::transaction(
                function () use (
                    $legacyProduct,
                    $variant8,
                    $variant10,
                    $now,
                    &$copiedFiles
                ): void {
                    /*
                     * El producto legacy conserva su ID
                     * y se convierte en Falco 8×42 UD.
                     */
                    DB::table('products')
                        ->where(
                            'id',
                            $legacyProduct->id
                        )
                        ->update([
                            'slug' =>
                                'natviewer-falco-8x42-ud',

                            'name_es' =>
                                'Natviewer Falco 8×42 UD',

                            'name_en' =>
                                'Natviewer Falco 8×42 UD',

                            'short_description_es' =>
                                'Binoculares 8×42 con lentes coated, prismas BAK-7 tipo techo, campo de visión de 93 m a 1000 m y enfoque mínimo de 3 m para observación de aves y naturaleza.',

                            'short_description_en' =>
                                '8×42 binoculars with coated lenses, BAK-7 roof prisms, a 93 m field of view at 1000 m and a 3 m minimum focus for birdwatching and nature observation.',

                            'description_es' =>
                                'Natviewer Falco 8×42 UD es un binocular para observación de aves, fauna y naturaleza. Ofrece 8× de aumento, objetivo de 42 mm, lentes coated, prismas BAK-7 tipo techo, campo de visión de 93 m a 1000 m, enfoque mínimo de 3 m, alivio ocular de 15 mm y pupila de salida de 5.25 mm. Su chasis es de policarbonato, pesa 675 g, incorpora oculares ajustables, ajuste dióptrico, enfoque central, resistencia a lluvia y niebla y compatibilidad con trípode.',

                            'description_en' =>
                                'Natviewer Falco 8×42 UD is a binocular for birdwatching, wildlife and nature observation. It offers 8× magnification, a 42 mm objective, coated lenses, BAK-7 roof prisms, a 93 m field of view at 1000 m, 3 m minimum focus, 15 mm eye relief and a 5.25 mm exit pupil. It has a polycarbonate chassis, weighs 675 g, and includes adjustable eyecups, diopter adjustment, central focusing, rain and fog resistance, and tripod compatibility.',

                            'meta_title_es' =>
                                'Binoculares Natviewer Falco 8×42 UD',

                            'meta_title_en' =>
                                'Natviewer Falco 8×42 UD Binoculars',

                            'meta_description_es' =>
                                'Binoculares Natviewer Falco 8×42 UD con prismas BAK-7 tipo techo, campo de visión de 93 m a 1000 m y enfoque mínimo de 3 m.',

                            'meta_description_en' =>
                                'Natviewer Falco 8×42 UD binoculars with BAK-7 roof prisms, a 93 m field of view at 1000 m and a 3 m minimum focus.',

                            'updated_at' =>
                                $now,
                        ]);

                    /*
                     * Crear o reutilizar el producto
                     * independiente Falco 10×42 UD.
                     */
                    $product10Id = DB::table(
                        'products'
                    )
                        ->where(
                            'slug',
                            'natviewer-falco-10x42-ud'
                        )
                        ->value('id');

                    $product10Data = [
                        'category_id' =>
                            $legacyProduct->category_id,

                        'brand_id' =>
                            $legacyProduct->brand_id,

                        'name_es' =>
                            'Natviewer Falco 10×42 UD',

                        'name_en' =>
                            'Natviewer Falco 10×42 UD',

                        'short_description_es' =>
                            'Binoculares 10×42 con lentes coated, prismas BAK-7 tipo techo, campo de visión de 89 m a 1000 m y enfoque mínimo de 3 m para observación de aves y naturaleza.',

                        'short_description_en' =>
                            '10×42 binoculars with coated lenses, BAK-7 roof prisms, an 89 m field of view at 1000 m and a 3 m minimum focus for birdwatching and nature observation.',

                        'description_es' =>
                            'Natviewer Falco 10×42 UD es un binocular para observación de aves, fauna y naturaleza. Ofrece 10× de aumento, objetivo de 42 mm, lentes coated, prismas BAK-7 tipo techo, campo de visión de 89 m a 1000 m, enfoque mínimo de 3 m, alivio ocular de 15 mm y pupila de salida de 4.2 mm. Su chasis es de policarbonato, pesa 687 g, incorpora oculares ajustables, ajuste dióptrico, enfoque central, resistencia a salpicaduras, lluvia y niebla y compatibilidad con trípode.',

                        'description_en' =>
                            'Natviewer Falco 10×42 UD is a binocular for birdwatching, wildlife and nature observation. It offers 10× magnification, a 42 mm objective, coated lenses, BAK-7 roof prisms, an 89 m field of view at 1000 m, 3 m minimum focus, 15 mm eye relief and a 4.2 mm exit pupil. It has a polycarbonate chassis, weighs 687 g, and includes adjustable eyecups, diopter adjustment, central focusing, splash, rain and fog resistance, and tripod compatibility.',

                        'status' =>
                            $legacyProduct->status,

                        'is_featured' =>
                            $legacyProduct->is_featured,

                        'meta_title_es' =>
                            'Binoculares Natviewer Falco 10×42 UD',

                        'meta_title_en' =>
                            'Natviewer Falco 10×42 UD Binoculars',

                        'meta_description_es' =>
                            'Binoculares Natviewer Falco 10×42 UD con prismas BAK-7 tipo techo, campo de visión de 89 m a 1000 m y enfoque mínimo de 3 m.',

                        'meta_description_en' =>
                            'Natviewer Falco 10×42 UD binoculars with BAK-7 roof prisms, an 89 m field of view at 1000 m and a 3 m minimum focus.',

                        'updated_at' =>
                            $now,
                    ];

                    if ($product10Id) {
                        DB::table('products')
                            ->where(
                                'id',
                                $product10Id
                            )
                            ->update(
                                $product10Data
                            );
                    } else {
                        $product10Id = DB::table(
                            'products'
                        )->insertGetId([
                            ...$product10Data,

                            'slug' =>
                                'natviewer-falco-10x42-ud',

                            'created_at' =>
                                $now,
                        ]);
                    }

                    /*
                     * Cada producto queda con su propia
                     * variante técnica predeterminada.
                     */
                    DB::table('product_variants')
                        ->where(
                            'id',
                            $variant8->id
                        )
                        ->update([
                            'product_id' =>
                                $legacyProduct->id,

                            'is_default' =>
                                true,

                            'sort_order' =>
                                1,

                            'updated_at' =>
                                $now,
                        ]);

                    DB::table('product_variants')
                        ->where(
                            'id',
                            $variant10->id
                        )
                        ->update([
                            'product_id' =>
                                $product10Id,

                            'is_default' =>
                                true,

                            'sort_order' =>
                                1,

                            'updated_at' =>
                                $now,
                        ]);

                    /*
                     * Las imágenes asociadas explícitamente
                     * a cada variante siguen a su producto.
                     */
                    DB::table('product_images')
                        ->where(
                            'variant_id',
                            $variant8->id
                        )
                        ->update([
                            'product_id' =>
                                $legacyProduct->id,

                            'updated_at' =>
                                $now,
                        ]);

                    DB::table('product_images')
                        ->where(
                            'variant_id',
                            $variant10->id
                        )
                        ->update([
                            'product_id' =>
                                $product10Id,

                            'updated_at' =>
                                $now,
                        ]);

                    /*
                     * Las imágenes generales pertenecían
                     * conceptualmente a ambos modelos.
                     *
                     * Se crea una copia física independiente
                     * para el producto 10×42 para evitar que
                     * eliminar una galería rompa la otra.
                     */
                    $generalImages = DB::table(
                        'product_images'
                    )
                        ->where(
                            'product_id',
                            $legacyProduct->id
                        )
                        ->whereNull(
                            'variant_id'
                        )
                        ->orderBy(
                            'sort_order'
                        )
                        ->orderBy(
                            'id'
                        )
                        ->get();

                    foreach (
                        $generalImages
                        as $generalImage
                    ) {
                        $disk =
                            $generalImage->disk
                            ?: 'public';

                        if (
                            ! Storage::disk($disk)
                                ->exists(
                                    $generalImage->path
                                )
                        ) {
                            continue;
                        }

                        $destinationPath =
                            'products/'
                            . $product10Id
                            . '/shared-'
                            . $generalImage->id
                            . '-'
                            . basename(
                                $generalImage->path
                            );

                        $alreadyRegistered =
                            DB::table(
                                'product_images'
                            )
                                ->where(
                                    'product_id',
                                    $product10Id
                                )
                                ->where(
                                    'path',
                                    $destinationPath
                                )
                                ->exists();

                        if ($alreadyRegistered) {
                            continue;
                        }

                        if (
                            ! Storage::disk($disk)
                                ->exists(
                                    $destinationPath
                                )
                        ) {
                            $copied = Storage::disk(
                                $disk
                            )->copy(
                                $generalImage->path,
                                $destinationPath
                            );

                            if (! $copied) {
                                throw new \RuntimeException(
                                    'No se pudo duplicar una imagen general de Natviewer Falco.'
                                );
                            }

                            $copiedFiles[] = [
                                'disk' =>
                                    $disk,

                                'path' =>
                                    $destinationPath,
                            ];
                        }

                        DB::table(
                            'product_images'
                        )->insert([
                            'product_id' =>
                                $product10Id,

                            'variant_id' =>
                                null,

                            'disk' =>
                                $disk,

                            'path' =>
                                $destinationPath,

                            'alt_es' =>
                                $generalImage->alt_es,

                            'alt_en' =>
                                $generalImage->alt_en,

                            'is_primary' =>
                                false,

                            'sort_order' =>
                                $generalImage->sort_order,

                            'created_at' =>
                                $now,

                            'updated_at' =>
                                $now,
                        ]);
                    }

                    /*
                     * Cada producto recibe exactamente
                     * una imagen principal propia.
                     *
                     * Se prioriza una imagen específica
                     * de su variante.
                     */
                    $this->assignPrimaryImage(
                        (int) $legacyProduct->id,
                        (int) $variant8->id,
                        $now
                    );

                    $this->assignPrimaryImage(
                        (int) $product10Id,
                        (int) $variant10->id,
                        $now
                    );

                    /*
                     * Las cotizaciones históricas conservan
                     * sus snapshots y su variant_id.
                     *
                     * Solo corregimos product_id para que
                     * corresponda al nuevo propietario de
                     * cada variante.
                     */
                    DB::table('quote_requests')
                        ->where(
                            'product_variant_id',
                            $variant8->id
                        )
                        ->update([
                            'product_id' =>
                                $legacyProduct->id,

                            'updated_at' =>
                                $now,
                        ]);

                    DB::table('quote_requests')
                        ->where(
                            'product_variant_id',
                            $variant10->id
                        )
                        ->update([
                            'product_id' =>
                                $product10Id,

                            'updated_at' =>
                                $now,
                        ]);
                }
            );
        } catch (\Throwable $exception) {
            foreach (
                $copiedFiles
                as $copiedFile
            ) {
                Storage::disk(
                    $copiedFile['disk']
                )->delete(
                    $copiedFile['path']
                );
            }

            throw $exception;
        }
    }

    public function down(): void
    {
        $product8 = DB::table('products')
            ->where(
                'slug',
                'natviewer-falco-8x42-ud'
            )
            ->first();

        if (! $product8) {
            return;
        }

        $product10 = DB::table('products')
            ->where(
                'slug',
                'natviewer-falco-10x42-ud'
            )
            ->first();

        $variant8 = DB::table(
            'product_variants'
        )
            ->where(
                'sku',
                'NV-FALCO-8X42-UD'
            )
            ->first();

        $variant10 = DB::table(
            'product_variants'
        )
            ->where(
                'sku',
                'NV-FALCO-10X42-UD'
            )
            ->first();

        $now = now();

        DB::transaction(
            function () use (
                $product8,
                $product10,
                $variant8,
                $variant10,
                $now
            ): void {
                if ($product10) {
                    /*
                     * Preservamos imágenes antes de
                     * eliminar el segundo producto.
                     */
                    DB::table('product_images')
                        ->where(
                            'product_id',
                            $product10->id
                        )
                        ->update([
                            'product_id' =>
                                $product8->id,

                            'is_primary' =>
                                false,

                            'updated_at' =>
                                $now,
                        ]);

                    DB::table('quote_requests')
                        ->where(
                            'product_id',
                            $product10->id
                        )
                        ->update([
                            'product_id' =>
                                $product8->id,

                            'updated_at' =>
                                $now,
                        ]);
                }

                if ($variant8) {
                    DB::table(
                        'product_variants'
                    )
                        ->where(
                            'id',
                            $variant8->id
                        )
                        ->update([
                            'product_id' =>
                                $product8->id,

                            'is_default' =>
                                true,

                            'sort_order' =>
                                1,

                            'updated_at' =>
                                $now,
                        ]);
                }

                if ($variant10) {
                    DB::table(
                        'product_variants'
                    )
                        ->where(
                            'id',
                            $variant10->id
                        )
                        ->update([
                            'product_id' =>
                                $product8->id,

                            'is_default' =>
                                false,

                            'sort_order' =>
                                2,

                            'updated_at' =>
                                $now,
                        ]);

                    DB::table(
                        'quote_requests'
                    )
                        ->where(
                            'product_variant_id',
                            $variant10->id
                        )
                        ->update([
                            'product_id' =>
                                $product8->id,

                            'updated_at' =>
                                $now,
                        ]);
                }

                DB::table('products')
                    ->where(
                        'id',
                        $product8->id
                    )
                    ->update([
                        'slug' =>
                            'natviewer-falco',

                        'name_es' =>
                            'Natviewer Falco',

                        'name_en' =>
                            'Natviewer Falco',

                        'short_description_es' =>
                            'Binoculares con lentes coated, prismas BAK-7 tipo techo y objetivo de 42 mm para observación de aves y naturaleza.',

                        'short_description_en' =>
                            'Binoculars with coated lenses, BAK-7 roof prisms and a 42 mm objective for birdwatching and nature observation.',

                        'description_es' =>
                            'Natviewer Falco es una línea de binoculares para observación de aves, fauna y naturaleza. Sus configuraciones actuales incorporan lentes coated, prismas BAK-7 tipo techo, objetivo de 42 mm, enfoque mínimo de 3 metros y alivio ocular de 15 mm. Las variantes disponibles permiten elegir diferentes niveles de aumento manteniendo una configuración orientada al uso en campo.',

                        'description_en' =>
                            'Natviewer Falco is a binocular line for birdwatching, wildlife and nature observation. Its current configurations feature coated lenses, BAK-7 roof prisms, a 42 mm objective, 3 m minimum focus and 15 mm eye relief. Available variants provide different magnification levels while maintaining a configuration intended for field observation.',

                        'meta_title_es' =>
                            'Binoculares Natviewer Falco 8×42 y 10×42 UD',

                        'meta_title_en' =>
                            'Natviewer Falco 8×42 and 10×42 UD Binoculars',

                        'meta_description_es' =>
                            'Conoce los binoculares Natviewer Falco con lentes coated, prismas BAK-7 tipo techo y objetivo de 42 mm para observación de aves y naturaleza.',

                        'meta_description_en' =>
                            'Discover Natviewer Falco binoculars with coated lenses, BAK-7 roof prisms and a 42 mm objective for birdwatching and nature observation.',

                        'updated_at' =>
                            $now,
                    ]);

                /*
                 * Al volver al modelo anterior solo puede
                 * existir una principal para el producto.
                 */
                DB::table('product_images')
                    ->where(
                        'product_id',
                        $product8->id
                    )
                    ->update([
                        'is_primary' =>
                            false,
                    ]);

                $preferredImageId =
                    $variant10
                        ? DB::table(
                            'product_images'
                        )
                            ->where(
                                'product_id',
                                $product8->id
                            )
                            ->where(
                                'variant_id',
                                $variant10->id
                            )
                            ->orderBy(
                                'sort_order'
                            )
                            ->orderBy(
                                'id'
                            )
                            ->value('id')
                        : null;

                $primaryImageId =
                    $preferredImageId
                    ?? DB::table(
                        'product_images'
                    )
                        ->where(
                            'product_id',
                            $product8->id
                        )
                        ->orderBy(
                            'sort_order'
                        )
                        ->orderBy(
                            'id'
                        )
                        ->value('id');

                if ($primaryImageId) {
                    DB::table(
                        'product_images'
                    )
                        ->where(
                            'id',
                            $primaryImageId
                        )
                        ->update([
                            'is_primary' =>
                                true,

                            'updated_at' =>
                                $now,
                        ]);
                }

                if ($product10) {
                    DB::table('products')
                        ->where(
                            'id',
                            $product10->id
                        )
                        ->delete();
                }
            }
        );
    }

    private function assignPrimaryImage(
        int $productId,
        int $preferredVariantId,
        $now
    ): void {
        DB::table('product_images')
            ->where(
                'product_id',
                $productId
            )
            ->update([
                'is_primary' =>
                    false,
            ]);

        $primaryImageId =
            DB::table('product_images')
                ->where(
                    'product_id',
                    $productId
                )
                ->where(
                    'variant_id',
                    $preferredVariantId
                )
                ->orderBy(
                    'sort_order'
                )
                ->orderBy(
                    'id'
                )
                ->value('id');

        if (! $primaryImageId) {
            $primaryImageId =
                DB::table('product_images')
                    ->where(
                        'product_id',
                        $productId
                    )
                    ->orderBy(
                        'sort_order'
                    )
                    ->orderBy(
                        'id'
                    )
                    ->value('id');
        }

        if ($primaryImageId) {
            DB::table('product_images')
                ->where(
                    'id',
                    $primaryImageId
                )
                ->update([
                    'is_primary' =>
                        true,

                    'updated_at' =>
                        $now,
                ]);
        }
    }
};