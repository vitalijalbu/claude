<?php

declare(strict_types=1);

namespace App\Actions\Category;

use App\DTO\Category\UpdateCategoryDTO;
use App\Models\Category;

class UpdateCategory
{
    public function handle(Category $category, UpdateCategoryDTO $dto): Category
    {
        $category->update($dto->toArray());

        return $category;
    }
}
