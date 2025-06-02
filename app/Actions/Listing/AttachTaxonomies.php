<?php

declare(strict_types=1);

namespace App\Actions\Listing;

use App\Models\Listing;
use App\Models\Taxonomy;
use Illuminate\Support\Facades\Log;

class AttachTaxonomies
{
    public function handle(Listing $listing, array $taxonomies): void
    {
        $taxonomyIds = [];
        $taxonomySlugs = array_column($taxonomies, 'value');

        $taxonomiesFromDb = Taxonomy::whereIn('slug', $taxonomySlugs)->get();
        $taxonomiesMap = $taxonomiesFromDb->keyBy('slug');

        foreach ($taxonomies as $item) {
            $taxonomyValue = $item['value'];

            if (isset($taxonomiesMap[$taxonomyValue])) {
                $taxonomyIds[] = $taxonomiesMap[$taxonomyValue]->id;
            } else {
                Log::warning("Taxonomy not found: {$taxonomyValue}");
            }
        }

        if (! empty($taxonomyIds)) {
            $listing->taxonomies()->sync($taxonomyIds);
        }
    }
}
