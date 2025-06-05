<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierData extends Model
{
    use HasFactory;

    protected $table = 'suppliers_data';

    protected $fillable = [
        'supplier_id',
        'email',
        'phone',
        'sent_to_erp',
        'data_sent_to_erp',
    ];

    protected $casts = [
        'data_sent_to_erp' => 'datetime',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
