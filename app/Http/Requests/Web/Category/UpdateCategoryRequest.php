<?php

declare(strict_types=1);

namespace App\Http\Requests\Web\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->route('category')),
            ],
            'description' => 'nullable|array',
            'description.*' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'media' => 'nullable|array',
        ];
    }
}
