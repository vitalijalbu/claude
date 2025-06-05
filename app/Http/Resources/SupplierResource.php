<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Supplier
 */
class SupplierResource extends JsonResource
{
    public function toArray(Request $request): array
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
            'status' => $this->status?->value,
            'priority' => $this->priority?->value,
            'pre_assessment_score' => [
                'label' => $this->pre_assessment_score?->value,
                'percentage' => $this->pre_assessment_score?->getPercentage(),
            ],
            'pre_assessment_date' => $this->pre_assessment_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'erp_data' => $this->whenLoaded('erpData', fn() => $this->erpData),
            'last_visit' => VisitResource::make($this->whenLoaded('lastVisit')),
            'visitable' => $this->whenAppended('visitable'),
            'organization' => OrganizationResource::make($this->whenLoaded('organization')),
        ];
    }
}
