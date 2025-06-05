<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->getLocalizedName(),
            'slug' => $this->slug,
            'description' => $this->getLocalizedDescription(),
            'icon' => $this->icon,
            'tags' => $this->whenLoaded('tags', function () {
                return $this->tags->map(fn ($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->getLocalizedName(),
                    'slug' => $tag->slug,
                    'icon' => $tag->icon,
                ]);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
