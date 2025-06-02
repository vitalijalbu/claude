<?php

declare(strict_types=1);

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class IndexCategories
{
    public function handle(array $params = []): Collection
    {
        return Category::query()
            ->get();
    }
}
