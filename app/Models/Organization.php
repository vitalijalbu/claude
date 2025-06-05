<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\OrganizationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id
 * @property string $name
 * @property string|null $organization_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User|null $users
 *
 * @method static OrganizationFactory factory($count = null, $state = [])
 * @method static Builder<static>|Organization newModelQuery()
 * @method static Builder<static>|Organization newQuery()
 * @method static Builder<static>|Organization query()
 * @method static Builder<static>|Organization whereAtlasOrganizationId($value)
 * @method static Builder<static>|Organization whereCreatedAt($value)
 * @method static Builder<static>|Organization whereId($value)
 * @method static Builder<static>|Organization whereName($value)
 * @method static Builder<static>|Organization whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Organization extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'atlas_organization_id',
    ];

    /**
     * The users that belong to the organization.
     */
    public function users(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
