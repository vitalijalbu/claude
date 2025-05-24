<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray($request): array
    {
        $filename = $this['filename'];
        $phone = $this['phone_number'];
        $listingId = $this['listing_id'];

        return [
            'url' => "/$phone/listings/$listingId/$filename",
            'srcset' => [
                [
                    'url' => "/$phone/listings/$listingId/conversions/sm-$filename",
                    'type' => pathinfo($filename, PATHINFO_EXTENSION),
                    'width' => 320,
                ],
                [
                    'url' => "/$phone/listings/$listingId/conversions/md-$filename",
                    'type' => pathinfo($filename, PATHINFO_EXTENSION),
                    'width' => 768,
                ],
                [
                    'url' => "/$phone/listings/$listingId/conversions/lg-$filename",
                    'type' => pathinfo($filename, PATHINFO_EXTENSION),
                    'width' => 1280,
                ],
            ],
        ];
    }
}
