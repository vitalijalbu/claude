<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Visit
 */
class VisitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'inspector' => UserResource::make($this->whenLoaded('inspector')),
            // 'is_draft' => $this->is_draft,
            'supplier_id' => $this->supplier_id,
            'form_id' => $this->form_id,
            'inspector_id' => $this->inspector_id,
            'date' => $this->date,
            'feedback' => $this->feedback,
            'result' => $this->result->value ?? null,
            'status' => $this->status->value ?? null,
            'certificate' => $this->certificate,
            'has_warning' => $this->has_warning,
            'critical_issue' => $this->critical_issue,
            'alert_sent' => $this->alert_sent,
            'alert_sent_at' => $this->alert_sent_at,
            'alert_sent_by' => $this->alert_sent_by,
            'supplier' => SupplierResource::make($this->whenLoaded('supplier')),
            'report' => VisitReportResource::make($this->whenLoaded('report')),
            'creator' => UserResource::make($this->whenLoaded('creator')),
            'updater' => UserResource::make($this->whenLoaded('updater')),
            'confirmator' => UserResource::make($this->whenLoaded('confirmator')),
            'review_requestor' => UserResource::make($this->whenLoaded('reviewRequestor')),
            'capacity_requestor' => UserResource::make($this->whenLoaded('capacityRequestor')),
            'confirmed_at' => $this->confirmed_at,
            'review_requested_at' => $this->review_requested_at,
            'capacity_requested_at' => $this->capacity_requested_at,
            'completed_at' => $this->completed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'review_requested_by' => $this->review_requested_by,
            'capacity_requested_by' => $this->capacity_requested_by,
            'completed_by' => $this->completed_by,
        ];
    }
}
