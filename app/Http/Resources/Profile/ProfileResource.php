<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into a default array (for collections).
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'phone_number' => $this->phone_number,
            'whatsapp_number' => $this->whatsapp_number,
            'name' => $this->name,
            'rating' => (float) $this->rating,
            'listings_count' => $this->listings_count,
            'category' => $this->whenLoaded('category', fn () => new CategoryResource($this->category)),
            'location' => $this->location,
            'city' => $this->whenLoaded('city', fn () => new CityResource($this->city)),
            'province' => $this->whenLoaded('city', fn () => new CityResource($this->province)),
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Custom transformation for single profile view.
     */
    public function toSingle(Request $request): array
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'age' => Carbon::parse($this->date_birth.'-01-01')->age,
            'name' => $this->name,
            'phone_number' => Str::replaceFirst('39', '', $this->phone_number),
            'whatsapp_number' => Str::replaceFirst('39', '', $this->whatsapp_number),
            'bio' => $this->bio,
            'lat' => $this->lat ?? null,
            'lon' => $this->lon ?? null,
            'working_hours' => $this->working_hours,
            'rating' => (float) $this->rating,
            'category' => $this->whenLoaded('category', fn () => new CategoryResource($this->category)),
            'location' => $this->location,
            'city' => $this->whenLoaded('city', fn () => new CityResource($this->city)),
            'province' => $this->whenLoaded('city', fn () => new CityResource($this->province)),
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
            'media' => MediaResource::collection(
                collect($this->media)->map(function ($filename) {
                    return [
                        'filename' => $filename,
                        'phone_number' => $this->phone_number,
                        'listing_id' => $this->id,
                    ];
                })
            ),
            'total_listings' => $this->listings_count,
            'listings' => $this->whenLoaded('listings', fn () => ListingResource::collection($this->listings)),
            'created_at' => $this->created_at,
        ];
    }
}
