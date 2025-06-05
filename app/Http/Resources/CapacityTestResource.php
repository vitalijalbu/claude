<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\CapacityTest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CapacityTest
 */
class CapacityTestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'supplier_id' => $this->supplier_id,
            'test_date' => $this->test_date,
            'result' => $this->result?->value,
            'status' => $this->status?->value,
            'test_deadline' => $this->test_deadline,
            'test_status' => $this->test_status,
            'product_type' => $this->product_type,
            'send_product' => $this->send_product,
            'supplier' => SupplierResource::make($this->whenLoaded('supplier')),
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'creator' => UserResource::make($this->whenLoaded('creator')),
            'updater' => UserResource::make($this->whenLoaded('updater')),
        ];
    }
}
