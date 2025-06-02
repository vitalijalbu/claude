<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ListingResource extends JsonResource
{
    /**
     * Trasformazione per collezione/listing ridotto
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'age' => Carbon::parse($this->date_birth.'-01-01')->age,
            'nationality' => $this->nationality,
            'slug' => $this->slug,
            'phone_number' => Str::replaceFirst('39', '', $this->phone_number),
            'whatsapp_number' => Str::replaceFirst('39', '', $this->whatsapp_number),
            'description' => $this->description,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'location' => $this->location,
            'city' => $this->whenLoaded('city', fn () => new CityResource($this->city)),
            'province' => $this->whenLoaded('province', fn () => new CityResource($this->province)),
            'category' => $this->whenLoaded('category', fn () => new CategoryResource($this->category)),
            'profile' => $this->whenLoaded('profile', fn () => new ProfileResource($this->profile)),
            'media' => MediaResource::collection(
                collect($this->media)->map(function ($filename) {
                    return [
                        'media' => $this->media,
                        'phone_number' => $this->phone_number,
                        'listing_id' => $this->id,
                    ];
                })
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Trasformazione completa per vista singola
     */
    public function toSingle(Request $request): array
    {
        return [
            'id' => $this->id,
            'age' => Carbon::parse($this->date_birth.'-01-01')->age,
            'nationality' => $this->nationality,
            'title' => $this->title,
            'slug' => $this->slug,
            'phone_number' => $this->phone_number,
            'whatsapp_number' => $this->whatsapp_number,
            'description' => $this->description,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'location' => $this->location,
            'city' => $this->whenLoaded('city', fn () => new CityResource($this->city)),
            'province' => $this->whenLoaded('province', fn () => new CityResource($this->province)),
            'category' => $this->whenLoaded('category', fn () => new CategoryResource($this->category)),
            'profile' => $this->whenLoaded('profile', fn () => new ProfileResource($this->profile)),
            'media' => $this->whenLoaded('media', function () {
                return MediaResource::collection($this->media);
            }),
            'taxonomies' => $this->whenLoaded('taxonomies', function () {
                return $this->taxonomies
                    ->load('group')
                    ->groupBy(fn ($taxonomy) => $taxonomy->group_id)
                    ->map(function ($items, $groupId) {
                        $group = $items->first()->group;

                        return [
                            'id' => $group->id,
                            'slug' => $group->slug,
                            'name' => $group->name,
                            'icon' => $group->icon ?? null,
                            'children' => $items->map(fn ($item) => [
                                'id' => $item->id,
                                'slug' => $item->slug,
                                'name' => $item->name,
                                'icon' => $item->icon,
                            ])->values(),
                        ];
                    })->values();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
