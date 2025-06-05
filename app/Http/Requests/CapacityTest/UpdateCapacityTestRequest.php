<?php

declare(strict_types=1);

namespace App\Http\Requests\CapacityTest;

use App\Enums\TestResult;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCapacityTestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'result' => ['nullable', 'string', Rule::in(TestResult::toValuesArray())],
            'notes' => ['nullable', 'string'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
