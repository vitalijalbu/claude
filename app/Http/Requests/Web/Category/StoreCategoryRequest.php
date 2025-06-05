<?php

declare(strict_types=1);

namespace App\Http\Requests\Web\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
            'media' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required',
            'name.*.required' => 'Category name is required for all languages',
            'slug.unique' => 'This slug is already taken',
        ];
    }
}
