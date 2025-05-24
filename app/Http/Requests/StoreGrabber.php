<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreGrabber extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'phone_number' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:255',
            'date_birth' => 'nullable|integer|min:1900|max:'.date('Y'),
            'city' => 'required|string|max:255|exists:geo_cities,name',
            'category' => 'required|string|max:255|exists:categories,slug',
            'media' => ['required', 'array', 'min:1', function ($attribute, $value, $fail) {
                foreach ($value as $url) {
                    if (! $this->isValidImageUrl($url)) {
                        $fail("The $attribute field must contain valid image URLs.");
                    }
                }
            }],
            'taxonomies' => 'nullable|array',
            'taxonomies.*.group' => 'string|max:255',
            'taxonomies.*.value' => 'string|max:255',
            'nationality' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
            'ref_site' => 'nullable|string|max:500',
            'is_verified' => 'nullable|boolean',
        ];
    }

    /**
     * Verify image URL valid.
     */
    private function isValidImageUrl(string $url): bool
    {
        $imageExtensions = ['jpeg', 'jpg', 'png', 'avif', 'webp'];
        $fileExtension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);

        return in_array(strtolower($fileExtension), $imageExtensions);
    }
}
