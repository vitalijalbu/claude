<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProfileSingleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone_number' => Str::replaceFirst('39', '', $this->phone_number),
            'whatsapp_number' => $this->whatsapp_number ? Str::replaceFirst('39', '', $this->whatsapp_number) : null,
            'bio' => $this->bio,
            'age' => $this->date_birth ? Carbon::parse($this->date_birth.'-01-01')->age : null,
            'nationality' => $this->nationality,
            'avatar' => $this->getFirstMediaUrl('avatar'),
            'rating' => (float) $this->rating,
            'working_hours' => $this->working_hours,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'website' => $this->website,
            'city' => $this->whenLoaded('city', fn () => new CityResource($this->city)),
            'province' => $this->whenLoaded('province', fn () => new ProvinceResource($this->province)),
            'category' => $this->whenLoaded('category', fn () => new CategorySingleResource($this->category)),
            'listings_count' => $this->whenCounted('listings'),
            'listings' => $this->whenLoaded('listings', fn () => ListingCollectionResource::collection($this->listings)),
            'taxonomies' => $this->whenLoaded('taxonomies', function () {
                return $this->taxonomies
                    ->load('group')
                    ->groupBy(fn ($taxonomy) => $taxonomy->group_id)
                    ->map(function ($items, $groupId) {
                        $group = $items->first()->group;

                        return [
                            'id' => $group->id,
                            'slug' => $group->slug,
                            'name' => $group->getLocalizedName(),
                            'icon' => $group->icon ?? null,
                            'children' => $items->map(fn ($item) => [
                                'id' => $item->id,
                                'slug' => $item->slug,
                                'name' => $item->getLocalizedName(),
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
