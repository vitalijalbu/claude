<?php

declare(strict_types=1);

namespace App\Http\Requests\VisitReport;

use App\Enums\InnovationLevel;
use App\Enums\TestResult;
use App\Models\CapacityTest;
use App\Models\VisitReport;
use App\Rules\VisitNewReportRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVisitReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [VisitReport::class, $this->route('visit')]);
    }

    public function rules(): array
    {
        $this->merge([
            'visit_id' => $this->route('visit')->id,
        ]);

        return [
            'visit_id' => ['required', 'integer', new VisitNewReportRule($this->route('visit'))],
            'production_test_id' => ['nullable', 'integer', Rule::exists(CapacityTest::class, 'id')],
            'version' => ['required', 'integer', 'min:1'],
            'content' => ['nullable', 'string'],
            'is_extended_from_test' => ['boolean'],
            'technical_skills' => ['nullable', 'string'],
            'technical_skills_note' => ['nullable', 'string'],
            'production_times_capacity' => ['nullable', 'string'],
            'production_times_capacity_note' => ['nullable', 'string'],
            'suitable_for' => ['nullable', 'string'],
            'suitable_for_note' => ['nullable', 'string'],
            'innovation_level' => ['nullable', 'string', Rule::enum(InnovationLevel::class)],
            'innovation_level_note' => ['nullable', 'string'],
            'technical_result' => ['nullable', 'string', Rule::enum(TestResult::class)],
            'comment' => ['nullable', 'string'],
            // 'is_draft' => ['nullable', 'boolean'],
        ];
    }
}
