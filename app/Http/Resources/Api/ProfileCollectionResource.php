<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileCollectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'whatsapp_number' => $this->whatsapp_number,
            'bio' => $this->bio,
            'age' => $this->date_birth ? Carbon::parse($this->date_birth.'-01-01')->age : null,
            'nationality' => $this->nationality,
            'avatar' => $this->getFirstMediaUrl('avatar'),
            'rating' => (float) $this->rating,
            'working_hours' => $this->working_hours,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'city' => $this->whenLoaded('city', fn () => new CityResource($this->city)),
            'province' => $this->whenLoaded('province', fn () => new ProvinceResource($this->province)),
            'category' => $this->whenLoaded('category', fn () => new CategoryResource($this->category)),
            'listings_count' => $this->whenCounted('listings'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
