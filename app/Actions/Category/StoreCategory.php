<?php

declare(strict_types=1);

namespace App\Actions\Category;

use App\DTO\Category\CategoryDTO;
use App\Models\Category;

class StoreCategory
{
    public function handle(CategoryDTO $dto): Category
    {
        return Category::create($dto->toArray());
    }
}
