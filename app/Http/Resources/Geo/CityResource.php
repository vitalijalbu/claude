<?php

declare(strict_types=1);

namespace App\Http\Resources\Geo;

use App\Http\Resources\Api\RegionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'region' => RegionResource::make($this->whenLoaded('region')),
            'province' => ProvinceResource::make($this->whenLoaded('province')),
        ];
    }
}
