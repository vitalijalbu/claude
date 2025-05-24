<?php

namespace App\Models;

use App\Models\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxonomy extends Model
{
    // use Cacheable;
    use SoftDeletes;

    protected $fillable = [
        'origin_id',
        'group_id',
        'site_id',
        'name',
    ];

    public function group()
    {
        return $this->belongsTo(TaxonomyGroup::class, 'group_id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
