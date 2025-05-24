<?php

namespace App\Models;

use App\Models\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    // use Cacheable;

    protected $fillable = [
        'slug',
        'url',
        'locale',
        'lang',
        'attributes',
    ];

    protected $casts = [
        'attributes' => 'json',
    ];
}
