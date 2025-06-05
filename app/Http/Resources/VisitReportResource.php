<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\VisitReport;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin VisitReport
 */
class VisitReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'visit_id' => $this->visit_id,
            'production_test_id' => $this->production_test_id,
            'version' => $this->version,
            'content' => $this->content,
            'is_extended_from_test' => $this->is_extended_from_test,
            'technical_skills' => $this->technical_skills,
            'technical_skills_note' => $this->technical_skills_note,
            'production_times_capacity' => $this->production_times_capacity,
            'production_times_capacity_note' => $this->production_times_capacity_note,
            'suitable_for' => $this->suitable_for,
            'suitable_for_note' => $this->suitable_for_note,
            'innovation_level' => $this->innovation_level,
            'innovation_level_note' => $this->innovation_level_note,
            'technical_result' => $this->technical_result,
            'comment' => $this->comment,
            'visit' => $this->whenLoaded('visit', fn() => new VisitResource($this->visit)),
            'production_test' => CapacityTestResource::make($this->whenLoaded('productionTest')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'creator' => UserResource::make($this->whenLoaded('creator')),
            'updater' => UserResource::make($this->whenLoaded('updater')),
        ];
    }
}
