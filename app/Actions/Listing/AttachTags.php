<?php

declare(strict_types=1);

namespace App\Actions\Listing;

use App\Models\Listing;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;

class AttachTags
{
    public function handle(Listing $listing, array $tags): void
    {
        $tagIds = [];
        $tagSlugs = array_column($tags, 'value');

        $tagsFromDb = Tag::whereIn('slug', $tagSlugs)->get();
        $tagsMap = $tagsFromDb->keyBy('slug');

        foreach ($tags as $item) {
            $tagValue = $item['value'];

            if (isset($tagsMap[$tagValue])) {
                $tagIds[] = $tagsMap[$tagValue]->id;
            } else {
                Log::warning("Tag not found: {$tagValue}");
            }
        }

        if (! empty($tagIds)) {
            $listing->tags()->sync($tagIds);
        }
    }
}
