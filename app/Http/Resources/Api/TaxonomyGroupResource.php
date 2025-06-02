<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxonomyGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->getLocalizedName(),
            'slug' => $this->slug,
            'description' => $this->getLocalizedDescription(),
            'icon' => $this->icon,
            'taxonomies' => $this->whenLoaded('taxonomies', function () {
                return $this->taxonomies->map(fn ($taxonomy) => [
                    'id' => $taxonomy->id,
                    'name' => $taxonomy->getLocalizedName(),
                    'slug' => $taxonomy->slug,
                    'icon' => $taxonomy->icon,
                ]);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
