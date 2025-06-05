<?php

declare(strict_types=1);

namespace App\Models;

use App\Atlas\Enums\RoleEnum;
use Carbon\CarbonImmutable;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $organization_id
 * @property string $atlas_member_id
 * @property string|null $remember_token
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Organization|null $organization
 *
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User newModelQuery()
 * @method static Builder<static>|User newQuery()
 * @method static Builder<static>|User query()
 * @method static Builder<static>|User whereAtlasMemberId($value)
 * @method static Builder<static>|User whereCreatedAt($value)
 * @method static Builder<static>|User whereEmail($value)
 * @method static Builder<static>|User whereId($value)
 * @method static Builder<static>|User whereName($value)
 * @method static Builder<static>|User whereOrganizationId($value)
 * @method static Builder<static>|User whereRememberToken($value)
 * @method static Builder<static>|User whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, HasRoles, HasUuids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'atlas_member_id',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'role' => RoleEnum::class,
    ];

    /**
     * The organization related to the user.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function getAuthIdentifierName(): string
    {
        return 'atlas_member_id';
    }

    public function isManager(): bool
    {
        return $this->hasRole(RoleEnum::TECH_MANAGER);
    }

    public function isTechnician(): bool
    {
        return $this->hasRole(RoleEnum::TECH_USER);
    }

    public function isInspector(): bool
    {
        return $this->hasRole(RoleEnum::TECH_USER);
    }

    public function newUniqueId(): string
    {
        return $this->atlas_member_id ?? (string) \Illuminate\Support\Str::uuid7();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->hasAnyPermission([$permission, "{$permission}-any"]);
    }
}
