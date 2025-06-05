<?php

declare(strict_types=1);

namespace App\Http\Requests\Visit;

use Illuminate\Foundation\Http\FormRequest;

class AddVisitWarningRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'critical_issue' => ['required', 'string', 'max:500'],
            'send_alert' => ['boolean'],
        ];
    }
}
