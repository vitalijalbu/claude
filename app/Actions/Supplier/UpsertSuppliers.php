<?php

declare(strict_types=1);

namespace App\Actions\Supplier;

use App\DTO\Supplier\SyncSupplierDto;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpsertSuppliers
{
    public function execute(array $suppliers): void
    {
        DB::transaction(function () use ($suppliers) {
            $data = collect($suppliers)
                ->map(fn (SyncSupplierDto $dto) => $dto->toArray())
                ->toArray();

            if (count($data)) {
                Supplier::upsert(
                    $data,
                    ['ympact_id', 'id'],
                    array_keys($data[0])
                );
            }

            Log::info('Upserted ' . count($data) . ' suppliers.');
        });
    }
}
