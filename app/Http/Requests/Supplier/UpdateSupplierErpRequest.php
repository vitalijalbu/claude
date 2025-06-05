<?php

declare(strict_types=1);

namespace App\Http\Requests\Supplier;

use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierErpRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supplier_id' => ['required', 'uuid', Rule::exists(Supplier::class, 'id')],
            'email' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'sent_to_erp' => ['required', 'date'],
        ];
    }
}
