<?php

declare(strict_types=1);

namespace App\DTO\Taxonomy;

readonly class TaxonomyGroupDTO
{
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $description = null,
        public ?string $icon = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'] ?? \Illuminate\Support\Str::slug($data['name']),
            description: $data['description'] ?? null,
            icon: $data['icon'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
        ];
    }
}
