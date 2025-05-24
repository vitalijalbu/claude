<?php

namespace App\Models;

use App\Models\Traits\Cacheable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxonomyGroup extends Model
{
    // use Cacheable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'slug',
    ];

    protected $with = [
        'taxonomies',
    ];

    public function taxonomies()
    {
        return $this->hasMany(Taxonomy::class, 'group_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site');
    }
}
