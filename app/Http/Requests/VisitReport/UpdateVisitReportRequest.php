<?php

declare(strict_types=1);

namespace App\Http\Requests\VisitReport;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVisitReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can(
            'update',
            [$this->route('visit')->report, $this->route('visit')]
        );
    }

    public function rules(): array
    {
        return [
            'version' => ['nullable', 'integer', 'min:1'],
            'content' => ['nullable', 'string'],
            'is_extended_from_test' => ['nullable', 'boolean'],
            'technical_skills' => ['nullable', 'string'],
            'technical_skills_note' => ['nullable', 'string'],
            'production_times_capacity' => ['nullable', 'string'],
            'production_times_capacity_note' => ['nullable', 'string'],
            'suitable_for' => ['nullable', 'string'],
            'suitable_for_note' => ['nullable', 'string'],
            'innovation_level' => ['nullable', 'string'],
            'innovation_level_note' => ['nullable', 'string'],
            'technical_result' => ['nullable', 'string'],
            'comment' => ['nullable', 'string'],
        ];
    }
}
