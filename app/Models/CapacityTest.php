<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TestResult;
use App\Enums\TestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

class CapacityTest extends Model
{
    use HasFactory, BlameableTrait;

    protected $table = 'production_capacity_tests';

    protected $fillable = [
        'supplier_id',
        'created_by',
        'test_date',
        'result',
        'status',
        'test_deadline',
        'test_status',
        'product_type',
        'send_product',
        'notes',
        'comments',
    ];

    protected $casts = [
        'test_date' => 'datetime',
        'test_deadline' => 'datetime',
        'send_product' => 'boolean',
        'result' => TestResult::class,
        'status' => TestStatus::class,
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(VisitReport::class, 'production_test_id');
    }
}
