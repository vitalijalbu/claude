<?php

declare(strict_types=1);

namespace App\Http\Requests\VisitReport;

use App\Http\Traits\HasPagination;
use Illuminate\Foundation\Http\FormRequest;

class IndexVisitReportsRequest extends FormRequest
{
    use HasPagination;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge($this->paginationRules(), [
            'status' => ['nullable', 'string'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'supplier.id' => ['nullable', 'integer'],
            'supplier.name' => ['nullable', 'string'],
        ]);
    }
}
