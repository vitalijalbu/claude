<?php

declare(strict_types=1);

namespace App\Actions\Search;

use Illuminate\Support\Collection;

final class IndexSearch
{
    public function execute(?string $query): Collection
    {

        return collect()
            ->merge($listings)
            ->merge($cities)
            ->merge($profiles);
    }
}
