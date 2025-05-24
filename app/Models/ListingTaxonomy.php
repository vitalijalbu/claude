<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingTaxonomy extends Model
{
    protected $fillable = [
        'listing_id',
        'taxonomy_id',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
