<?php

declare(strict_types=1);

namespace App\Http\Requests\Visit;

use App\Rules\SuppliersNewVisitRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Visit::class);
    }

    public function rules(): array
    {
        return [
            'supplier_ids' => ['required', 'array', 'min:1', new SuppliersNewVisitRule()],
            'supplier_ids.*' => ['required', 'uuid'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            // 'is_draft' => ['nullable', 'boolean'],
            'inspector_atlas_id' => ['required', 'string', 'uuid'],
            'inspector_name' => ['required', 'string'],
            'inspector_email' => ['required', 'email'],
        ];
    }
}
