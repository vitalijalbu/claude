<?php

declare(strict_types=1);

namespace App\Http\Requests\Supplier;

use App\Enums\SupplierPriority;
use App\Enums\SupplierScore;
use App\Enums\SupplierStatus;
use App\Http\Traits\HasPagination;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexSuppliersRequest extends FormRequest
{
    use HasPagination;

    public function rules(): array
    {
        return array_merge($this->paginationRules(), [
            'status' => ['nullable', 'string', Rule::in(SupplierStatus::toValuesArray())],
            'id' => ['nullable', 'integer'],
            'name' => ['nullable', 'string'],
            'ympact_id' => ['nullable', 'string'],
            'country' => 'nullable|string|max:255',
            'status' => ['nullable', Rule::enum(SupplierStatus::class)],
            'priority' => ['nullable', Rule::enum(SupplierPriority::class)],
            'pre_assessment_score' => ['nullable', Rule::enum(SupplierScore::class)],
        ]);
    }
}
