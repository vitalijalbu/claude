<?php

declare(strict_types=1);

namespace App\DTO\Auth;

readonly class UpdateUserDTO
{
    public function __construct(
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?string $email = null,
        public ?string $username = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            first_name: $data['first_name'] ?? null,
            last_name: $data['last_name'] ?? null,
            email: $data['email'] ?? null,
            username: $data['username'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'username' => $this->username,
        ], fn ($value) => $value !== null);
    }
}
