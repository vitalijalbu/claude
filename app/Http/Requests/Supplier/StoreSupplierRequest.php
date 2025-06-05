<?php

declare(strict_types=1);

namespace App\Http\Requests\Supplier;

use App\Enums\SupplierPriority;
use App\Enums\SupplierScore;
use App\Enums\SupplierStatus;
use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ympact_id' => ['required', 'string', Rule::unique(Supplier::class, 'ympact_id')],
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'vat' => ['required', 'string', Rule::unique(Supplier::class, 'vat')],
            'email' => ['required', 'email', Rule::unique(Supplier::class, 'email')],
            'phone' => ['required', 'string'],
            'country' => ['nullable', 'string'],
            'province' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string'],
            'status' => ['required', Rule::in(SupplierStatus::toValuesArray())],
            'pre_assessment_score' => ['required', Rule::in(SupplierScore::toNamesArray())],
            'pre_assessment_date' => ['required', 'date'],
            'priority' => ['required', Rule::in(SupplierPriority::toValuesArray())],
        ];
    }

    public function messages(): array
    {
        return [
            'status' => 'The selected status is invalid. It must be one of: ' . implode(', ', SupplierStatus::toValuesArray()),
            'pre_assessment_score' => 'The selected pre-assessment score is invalid. It must be one of: ' . implode(', ', SupplierScore::toValuesArray()),
            'priority' => 'The selected priority is invalid. It must be one of: ' . implode(', ', SupplierPriority::toValuesArray()),
        ];
    }
}
