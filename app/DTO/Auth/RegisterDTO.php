<?php

declare(strict_types=1);

namespace App\DTO\Auth;

readonly class RegisterDTO
{
    public function __construct(
        public string $first_name,
        public ?string $last_name,
        public string $email,
        public string $password,
        public ?string $username = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            first_name: $data['first_name'],
            last_name: $data['last_name'] ?? null,
            email: $data['email'],
            password: $data['password'],
            username: $data['username'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password,
            'username' => $this->username,
        ];
    }
}
