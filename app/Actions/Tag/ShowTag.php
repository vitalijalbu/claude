<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\TagGroup;

class ShowTag
{
    public function handle(TagGroup $group): ?TagGroup
    {
        return TagGroup::with('tags')->first();
    }
}
