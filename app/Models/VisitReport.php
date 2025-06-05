<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class VisitReport extends Model
{
    use HasFactory, SoftDeletes, BlameableTrait;

    protected $guarded = [
        // 'visit_id',
        // 'production_test_id',
        // 'created_by',
        // 'version',
        // 'content',
        // 'is_extended_from_test',
        // 'technical_skills',
        // 'technical_skills_note',
        // 'production_times_capacity',
        // 'production_times_capacity_note',
        // 'suitable_for',
        // 'suitable_for_note',
        // 'innovation_level',
        // 'innovation_level_note',
        // 'technical_result',
        // 'comment',
    ];

    protected $casts = [
        'visit_id' => 'integer',
        'production_test_id' => 'integer',
        'version' => 'integer',
        'is_extended_from_test' => 'boolean',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class, 'visit_id');
    }

    public function productionTest(): BelongsTo
    {
        return $this->belongsTo(CapacityTest::class, 'production_test_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
