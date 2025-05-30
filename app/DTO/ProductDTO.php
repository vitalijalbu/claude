<?php
declare(strict_types=1);
namespace App\DTO;

class ProductDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $slug = null,
        public readonly ?string $description = null,
        public readonly ?array $attribute_data = [],
        public readonly ?int $brand_id = null,
        public readonly ?array $collection_ids = [],
        public readonly ?string $status = 'published',
        public readonly ?array $variants = [],
        public readonly ?string $external_id = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            attribute_data: $data['attribute_data'] ?? [],
            brand_id: $data['brand_id'] ?? null,
            collection_ids: $data['collection_ids'] ?? [],
            status: $data['status'] ?? 'published',
            variants: $data['variants'] ?? [],
            external_id: $data['external_id'] ?? null
        );
    }
}