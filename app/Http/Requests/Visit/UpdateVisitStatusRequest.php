<?php

declare(strict_types=1);

namespace App\Http\Requests\Visit;

use App\Enums\VisitStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVisitStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(VisitStatus::toValuesArray())],
        ];
    }
}
