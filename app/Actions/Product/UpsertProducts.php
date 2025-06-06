<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\DTO\Product\ProductDTO;
use Illuminate\Support\Facades\DB;
use Lunar\Models\Product;

class UpsertProducts
{
    /**
     * Execute bulk product upsert operation
     *
     * @param  ProductDTO[]  $dtos  Array of ProductDTO objects
     * @return array ['success' => bool, 'affected_rows' => int]
     */
    public function execute(array $dtos): array
    {
        try {
            $affectedRows = 0;

            DB::transaction(function () use ($dtos, &$affectedRows) {
                $data = [];

                foreach ($dtos as $dto) {
                    // Validate that each item is a ProductDTO
                    if (! $dto instanceof ProductDTO) {
                        throw new \InvalidArgumentException('All items must be ProductDTO instances');
                    }

                    $productData = $dto->toArray();
                    $productData['updated_at'] = now();
                    $productData['created_at'] = now();
                    $data[] = $productData;
                }

                // Perform bulk upsert
                $affectedRows = Product::upsert(
                    $data,
                    ['sku'],
                    array_keys($data[0])
                );
            });

            return [
                'success' => true,
                'affected_rows' => $affectedRows,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'affected_rows' => 0,
            ];
        }
    }
}
