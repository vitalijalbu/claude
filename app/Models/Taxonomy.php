<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Taxonomy extends Model
{
    use HasTranslations, SoftDeletes;

    public array $translatable = ['name'];

    protected $fillable = [
        'origin_id', 'group_id', 'site_id', 'name', 'slug', 'icon',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(TaxonomyGroup::class, 'group_id');
    }

    public function getLocalizedName(?string $locale = null): string
    {
        return $this->getTranslation('name', $locale ?? app()->getLocale());
    }
}
