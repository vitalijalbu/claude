<?php

declare(strict_types=1);

namespace App\DTO\Listing;

readonly class UpdateListingDTO
{
    public function __construct(
        public string $title,
        public string $slug,
        public string $description,
        public string $phone_number,
        public ?string $whatsapp_number = null,
        public ?int $date_birth = null,
        public ?string $location = null,
        public ?float $lat = null,
        public ?float $lon = null,
        public ?int $category_id = null,
        public ?int $city_id = null,
        public ?int $profile_id = null,
        public ?string $ref_site = null,
        public bool $is_verified = false,
        public bool $is_featured = false,
        public ?array $media = null,
        public ?array $taxonomies = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            title: $data['title'],
            slug: $data['slug'] ?? \Illuminate\Support\Str::slug($data['title']),
            description: $data['description'],
            phone_number: $data['phone_number'],
            whatsapp_number: $data['whatsapp_number'] ?? null,
            date_birth: $data['date_birth'] ?? null,
            location: $data['location'] ?? null,
            lat: $data['lat'] ?? null,
            lon: $data['lon'] ?? null,
            category_id: $data['category_id'] ?? null,
            city_id: $data['city_id'] ?? null,
            profile_id: $data['profile_id'] ?? null,
            ref_site: $data['ref_site'] ?? null,
            is_verified: $data['is_verified'] ?? false,
            is_featured: $data['is_featured'] ?? false,
            media: $data['media'] ?? null,
            taxonomies: $data['taxonomies'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'phone_number' => $this->phone_number,
            'whatsapp_number' => $this->whatsapp_number,
            'date_birth' => $this->date_birth,
            'location' => $this->location,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'category_id' => $this->category_id,
            'city_id' => $this->city_id,
            'profile_id' => $this->profile_id,
            'ref_site' => $this->ref_site,
            'is_verified' => $this->is_verified,
            'is_featured' => $this->is_featured,
        ];
    }
}