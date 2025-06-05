<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingTag extends Model
{
    protected $fillable = [
        'listing_id',
        'tag_id',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
