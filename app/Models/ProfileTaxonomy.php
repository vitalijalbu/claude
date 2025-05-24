<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileTaxonomy extends Model
{
    protected $fillable = [
        'profile_id',
        'taxonomy_id',
    ];
}
