<?php

declare(strict_types=1);

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;

final class StoreCategory extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'site_id' => 'required|integer|exists:sites,id',
            'slug' => 'required|string|unique:categories,slug',
            'title' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
