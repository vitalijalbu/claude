<?php

declare(strict_types=1);

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->original_url,
            'type' => $this->type,
            'sm' => $this->getUrl('sm'),
            'md' => $this->getUrl('md'),
            'lg' => $this->getUrl('lg'),
        ];
    }
}