<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class TaxonomyGroup extends Model
{
    use HasTranslations, SoftDeletes;

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'name', 'description', 'slug', 'icon',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    public function taxonomies(): HasMany
    {
        return $this->hasMany(Taxonomy::class, 'group_id');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function getLocalizedName(?string $locale = null): string
    {
        return $this->getTranslation('name', $locale ?? app()->getLocale());
    }

    public function getLocalizedDescription(?string $locale = null): ?string
    {
        return $this->getTranslation('description', $locale ?? app()->getLocale());
    }
}
