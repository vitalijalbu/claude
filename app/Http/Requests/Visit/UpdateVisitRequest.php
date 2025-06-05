<?php

declare(strict_types=1);

namespace App\Http\Requests\Visit;

use App\Enums\VisitStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('visit'));
    }

    public function rules(): array
    {
        return [
            'date' => ['date'],
            'inspector_atlas_id' => ['string', 'uuid'],
            'inspector_name' => ['string'],
            'inspector_email' => ['string', 'email'],
        ];
    }
}
