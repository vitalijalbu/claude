<?php

declare(strict_types=1);

namespace App\Actions\Category;

use App\Models\Category;

class ShowCategory
{
    public function handle(string $slug): Category
    {
        return Category::where('slug', $slug)->firstOrFail();
    }
}
