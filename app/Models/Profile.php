<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Geo\City;
use App\Models\Geo\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'user_id', 'city_id', 'phone_number', 'nationality', 'whatsapp_number',
        'lon', 'lat', 'bio', 'avatar', 'website', 'working_hours', 'date_birth', 'rating',
    ];

    protected $casts = [
        'working_hours' => 'json',
        'rating' => 'float',
        'media' => 'array',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function province(): HasOneThrough
    {
        return $this->hasOneThrough(Province::class, City::class, 'id', 'id', 'city_id', 'province_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'profile_tags');
    }

    // Scopes
    public function scopeRatingRange($query, $min, $max)
    {
        return $query->whereBetween('rating', [$min, $max]);
    }

    public function scopeWithActiveListings($query)
    {
        return $query->whereHas('listings', function ($q) {
            $q->where('is_active', true);
        });
    }
}
