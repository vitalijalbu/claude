<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VisitResult;
use App\Enums\VisitStatus;
use App\Exceptions\VisitStateMachineException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

/**
 * @property ?VisitReport $report
 * @property ?Supplier $supplier
 * @property \Illuminate\Support\Carbon $date
 * @property \Illuminate\Support\Carbon|null $alert_sent_at
 * @property \Illuminate\Support\Carbon|null $planned_at
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $confirmed_at
 * @property \Illuminate\Support\Carbon|null $review_requested_at
 * @property \Illuminate\Support\Carbon|null $request_capacity_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property ?User $inspector
 * @property ?User $confirmator
 * @property ?User $reviewer
 * @property ?User $completer
 * @property ?User $rejector
 */
class Visit extends Model
{
    use BlameableTrait, HasFactory, SoftDeletes;

    protected $table = 'technical_visits';

    protected $guarded = [];

    protected $casts = [
        'date' => 'datetime',
        'alert_sent_at' => 'datetime',
        'request_capacity_at' => 'datetime',
        'completed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'review_requested_at' => 'datetime',
        'capacity_requested_at' => 'datetime',
        'rejected_at' => 'datetime',
        'has_warning' => 'boolean',
        'alert_sent' => 'boolean',
        'status' => VisitStatus::class,
        'result' => VisitResult::class,
    ];

    protected $attributes = [
        'status' => VisitStatus::PLANNED,
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function productionCapacityTests(): HasMany
    {
        return $this->hasMany(CapacityTest::class, 'visit_id');
    }

    public function report(): HasOne
    {
        return $this->hasOne(VisitReport::class, 'visit_id');
    }

    public function state(): \App\StateMachines\Visit\BaseState
    {
        return match ($this->status) {
            VisitStatus::PLANNED => new \App\StateMachines\Visit\PlannedState($this),
            VisitStatus::CONFIRM_PLANNING => new \App\StateMachines\Visit\ConfirmedState($this),
            VisitStatus::TO_REVIEW => new \App\StateMachines\Visit\ReviewState($this),
            VisitStatus::TEST_NEEDED => new \App\StateMachines\Visit\TestNeededState($this),
            VisitStatus::COMPLETED => new \App\StateMachines\Visit\CompletedState($this),
            default => throw new VisitStateMachineException('Unknown state')
        };
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function confirmator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function reviewRequestor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'review_requested_by');
    }

    public function capacityRequestor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'capacity_requested_by');
    }

    public function completer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $visit = parent::resolveRouteBinding($value, $field);

        return $visit ?
            $visit->loadMissing(['report', 'inspector'])
            : null;
    }
}
