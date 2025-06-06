<?php

declare(strict_types=1);

namespace App\Actions\Collection;

use App\Http\Resources\CollectionResource;
use Lunar\Models\Collection;

class ShowCollection
{
    public function execute(Collection $collection)
    {
        $collection->load([
            'thumbnail',
            'children',
            'products',
        ]);

        return new CollectionResource($collection);
    }
}
