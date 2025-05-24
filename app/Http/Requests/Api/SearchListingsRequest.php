<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

final class SearchListingsRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'city' => 'required|integer|exists:sites,id',
            'price' => 'nullable|integer|between:0,5',
            'category' => 'nullable|integer|between:0,5',
            'real_photos' => 'nullable|boolean',
        ];
    }
}
