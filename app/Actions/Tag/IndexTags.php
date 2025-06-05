<?php

declare(strict_types=1);

namespace App\Actions\Tag;

use App\Models\TagGroup;
use Illuminate\Database\Eloquent\Collection;

class IndexTags
{
    public function handle(array $params = []): Collection
    {
        $query = TagGroup::with('tags');

        return $query->orderBy('name')->get();
    }
}
