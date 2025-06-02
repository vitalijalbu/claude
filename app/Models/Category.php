<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'media',
        'icon',
    ];

    protected $casts = [
        'media' => 'array',
        'name' => 'array',
        'description' => 'array',
    ];

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function scopeWithListings($query)
    {
        return $query->has('listings');
    }

    public function getLocalizedName(?string $locale = null): string
    {
        return $this->getTranslation('name', $locale ?? app()->getLocale());
    }
}
