<?php

declare(strict_types=1);

namespace App\Http\Requests\Taxonomy;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxonomyGroupRequest extends FormRequest
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
            'slug' => 'required|string|max:255|unique:taxonomy_groups,slug',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
        ];
    }
}
