<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SupplierPriority;
use App\Enums\SupplierScore;
use App\Enums\SupplierStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property ?bool $visitable
 */
class Supplier extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'id',
        'ympact_id',
        'organization_id',
        'name',
        'address',
        'vat',
        'email',
        'phone',
        'country',
        'province',
        'city',
        'postal_code',
        'status',
        'pre_assessment_score',
        'pre_assessment_date',
        'priority',
    ];

    protected $casts = [
        'pre_assessment_date' => 'datetime',
        'status' => SupplierStatus::class,
        'pre_assessment_score' => SupplierScore::class,
        'priority' => SupplierPriority::class,
    ];

    protected $keyType = 'string';

    protected $with = [
        'organization',
        'erpData',
    ];

    protected $appends = [
        'visitable',
    ];

    public function erpData(): HasOne
    {
        return $this->hasOne(SupplierData::class, 'supplier_id');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function capacityTests(): HasMany
    {
        return $this->hasMany(CapacityTest::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function lastVisit(): HasOne
    {
        return $this->hasOne(Visit::class)
            ->latestOfMany();
    }

    protected function visitable(): Attribute
    {
        return Attribute::get(
            fn() => $this->relationLoaded('lastVisit')
                ? ! $this->lastVisit
                : null
        );
    }
}
