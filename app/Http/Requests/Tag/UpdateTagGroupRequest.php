<?php

declare(strict_types=1);

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagGroupRequest extends FormRequest
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
                Rule::unique('tag_groups', 'slug')->ignore($this->route('tagGroup')),
            ],
            'description' => 'nullable|array',
            'description.*' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:255',
        ];
    }
}
