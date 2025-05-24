<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'country' => [
                'id' => $this->country->id,
                'slug' => $this->country->slug,
                'name' => $this->country->name,
            ],
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }
}
