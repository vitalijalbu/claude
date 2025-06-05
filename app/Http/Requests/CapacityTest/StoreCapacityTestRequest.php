<?php

declare(strict_types=1);

namespace App\Http\Requests\CapacityTest;

use App\Enums\TestResult;
use App\Enums\TestStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCapacityTestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'test_date' => ['required', 'date'],
            'result' => ['nullable', 'string', Rule::in(TestResult::toValuesArray())],
            'status' => ['nullable', 'string', Rule::in(TestStatus::toValuesArray())],
            'test_deadline' => ['nullable', 'date'],
            'test_status' => ['nullable', 'string'],
            'product_type' => ['nullable', 'string'],
            'send_product' => ['boolean'],
            // 'is_draft' => ['nullable', 'boolean'],
        ];
    }
}
