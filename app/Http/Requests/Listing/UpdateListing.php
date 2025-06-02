<?php

declare(strict_types=1);

namespace App\Http\Requests\Listing;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'date_birth' => 'nullable|integer|min:1900|max:'.date('Y'),
            'location' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric|between:-90,90',
            'lon' => 'nullable|numeric|between:-180,180',
            'category_id' => 'required|integer|exists:categories,id',
            'city_id' => 'required|integer|exists:geo_cities,id',
            'profile_id' => 'nullable|integer|exists:profiles,id',
            'ref_site' => 'nullable|string|max:500',
            'is_verified' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'media' => 'nullable|array',
            'taxonomies' => 'nullable|array',
            'taxonomies.*.group' => 'required_with:taxonomies|string',
            'taxonomies.*.value' => 'required_with:taxonomies|string',
        ];
    }
}