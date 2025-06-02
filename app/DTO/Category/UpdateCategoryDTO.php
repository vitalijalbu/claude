<?php

declare(strict_types=1);

namespace App\DTO\Category;

readonly class UpdateCategoryDTO
{
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $description = null,
        public ?string $icon = null,
        public ?array $media = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'],
            description: $data['description'] ?? null,
            icon: $data['icon'] ?? null,
            media: $data['media'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'media' => $this->media,
        ];
    }
}
