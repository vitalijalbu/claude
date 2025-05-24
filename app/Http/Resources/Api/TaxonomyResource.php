<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxonomyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'icon' => $this->icon ?? null,
            'description' => $this->description,
            'children' => $this->whenLoaded('taxonomies', function () {
                return $this->taxonomies->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'slug' => $child->slug,
                        'name' => $child->name,
                        'icon' => $child->icon ?? null,
                        'description' => $child->description,
                    ];
                });
            }),
        ];
    }
}
