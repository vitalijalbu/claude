<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ExploreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'label' => $this->name ?? $this->title ?? $this->full_name ?? 'N/A',
            'type' => $this->type,
            'slug' => $this->slug,
        ];
    }
}
