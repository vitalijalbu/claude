<?php

declare(strict_types=1);

namespace App\DTO\Supplier;

use Spatie\LaravelData\Data;

final class SyncSupplierDto extends Data
{
    public ?int $id;

    public int $ympact_id;

    public ?int $organization_id;

    public string $name;

    public ?string $address;

    public ?string $vat;

    public ?string $email;

    public ?string $phone;

    public ?string $country;

    public ?string $province;

    public ?string $city;

    public ?string $postal_code;

    public ?string $status;

    public ?float $pre_assessment_score;

    public ?string $pre_assessment_date;

    public ?int $priority;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'ympact_id' => $this->ympact_id,
            'organization_id' => $this->organization_id,
            'name' => $this->name,
            'address' => $this->address,
            'vat' => $this->vat,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'province' => $this->province,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'status' => $this->status,
            'pre_assessment_score' => $this->pre_assessment_score,
            'pre_assessment_date' => $this->pre_assessment_date,
            'priority' => $this->priority,
        ];
    }
}
