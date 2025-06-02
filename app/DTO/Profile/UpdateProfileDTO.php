<?php

declare(strict_types=1);

namespace App\DTO\Profile;

readonly class UpdateProfileDTO
{
    public function __construct(
        public string $name,
        public string $phone_number,
        public ?string $whatsapp_number = null,
        public ?string $email = null,
        public ?string $bio = null,
        public ?string $nationality = null,
        public ?int $date_birth = null,
        public ?int $city_id = null,
        public ?float $lat = null,
        public ?float $lon = null,
        public ?string $avatar = null,
        public ?array $media = null,
        public ?array $working_hours = null,
        public ?float $rating = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            phone_number: $data['phone_number'],
            whatsapp_number: $data['whatsapp_number'] ?? null,
            email: $data['email'] ?? null,
            bio: $data['bio'] ?? null,
            nationality: $data['nationality'] ?? null,
            date_birth: $data['date_birth'] ?? null,
            city_id: $data['city_id'] ?? null,
            lat: $data['lat'] ?? null,
            lon: $data['lon'] ?? null,
            avatar: $data['avatar'] ?? null,
            media: $data['media'] ?? null,
            working_hours: $data['working_hours'] ?? null,
            rating: $data['rating'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'whatsapp_number' => $this->whatsapp_number,
            'email' => $this->email,
            'bio' => $this->bio,
            'nationality' => $this->nationality,
            'date_birth' => $this->date_birth,
            'city_id' => $this->city_id,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'avatar' => $this->avatar,
            'media' => $this->media,
            'working_hours' => $this->working_hours,
            'rating' => $this->rating,
        ];
    }
}
