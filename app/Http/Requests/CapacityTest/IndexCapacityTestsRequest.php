<?php

declare(strict_types=1);

namespace App\Http\Requests\CapacityTest;

use App\Http\Traits\HasPagination;
use Illuminate\Foundation\Http\FormRequest;

class IndexCapacityTestsRequest extends FormRequest
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
            'result' => ['nullable', 'string'],
            'supplier_id' => ['nullable', 'uuid'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'product_type' => ['nullable', 'string'],
        ]);
    }
}
