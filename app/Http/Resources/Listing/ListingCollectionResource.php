<?php

declare(strict_types=1);

namespace App\Http\Resources\Listing;

use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\MediaResource;
use App\Http\Resources\Api\ProfileResource;
use App\Http\Resources\Geo\ProvinceResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ListingCollectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'age' => $this->date_birth ? Carbon::parse($this->date_birth.'-01-01')->age : null,
            'phone_number' => $this->phone_number,
            'whatsapp_number' => $this->whatsapp_number ? $this->whatsapp_number : null,
            'description' => Str::limit($this->description, 150),
            'is_verified' => $this->is_verified,
            'is_featured' => $this->is_featured,
            'location' => $this->location,
            'province' => ProvinceResource::make($this->whenLoaded('province')),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'profile' => ProfileResource::make($this->whenLoaded('profile')),
            'media' => MediaResource::collection(
                collect($this->media)->map(function ($filename) {
                    return [
                        'media' => $this->media,
                        'phone_number' => $this->phone_number,
                        'listing_id' => $this->id,
                    ];
                })
            ),
            'featured_image' => $this->getFeaturedImageUrl('medium'),
            'media_count' => $this->getMedia('images')->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
