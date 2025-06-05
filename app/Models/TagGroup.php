<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class TagGroup extends Model
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

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class, 'group_id');
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
