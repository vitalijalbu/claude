<?php

namespace App\DTO;

class ListingDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $slug,
        public readonly ?string $phoneNumber = null,
        public readonly ?array $additionalData = [],
    ) {}

    /**
     * Crea un'istanza di DTO dai dati di input trasformandoli
     * nel formato corretto per l'utilizzo nei modelli
     */
    public static function fromRequest(array $data): self
    {
        // Qui possiamo personalizzare la creazione del DTO
        // Ad esempio, combinando first_name e last_name in title
        $fullName = trim($data['first_name'].' '.$data['last_name']);

        return new self(
            title: $fullName,
            slug: $data['slug'],
            phoneNumber: $data['phone_number'] ?? null,
            additionalData: isset($data['additional_data']) ? json_decode($data['additional_data'], true) : [],
        );
    }

    /**
     * Converte il DTO in array per essere usato nei modelli
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'phone_number' => $this->phoneNumber,
            'additional_data' => $this->additionalData,
        ];
    }
}
