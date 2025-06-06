<?php

declare(strict_types=1);

namespace App\Actions\Collection;

use App\Http\Resources\CollectionResource;
use Lunar\Models\Contracts\CollectionGroup;

class ShowCollection
{
    public function execute(CollectionGroup $collection)
    {
        // $collection->load([
        //     'collections',
        // ]);

        return new CollectionResource($collection);
    }
}
