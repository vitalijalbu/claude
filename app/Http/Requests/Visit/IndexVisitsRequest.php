<?php

declare(strict_types=1);

namespace App\Http\Requests\Visit;

use App\Enums\SupplierPriority;
use App\Enums\SupplierScore;
use App\Enums\SupplierStatus;
use App\Enums\VisitStatus;
use App\Http\Traits\HasPagination;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexVisitsRequest extends FormRequest
{
    use HasPagination;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge($this->paginationRules(), [
            'status' => ['nullable', 'string', Rule::in(VisitStatus::toValuesArray())],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'supplier.id' => ['nullable', 'integer'],
            'supplier.name' => ['nullable', 'string'],
            'supplier.ympact_id' => ['nullable', 'string'],
            'supplier.country' => 'nullable|string|max:255',
            'supplier.status' => ['nullable', Rule::enum(SupplierStatus::class)],
            'supplier.priority' => ['nullable', Rule::enum(SupplierPriority::class)],
            'supplier.pre_assessment_score' => ['nullable', Rule::enum(SupplierScore::class)],
        ]);
    }
}
