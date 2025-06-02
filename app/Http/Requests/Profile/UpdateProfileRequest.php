<?php

declare(strict_types=1);

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $profileId = $this->route('profile')?->id;

        return [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:profiles,phone_number,'.$profileId,
            'whatsapp_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'bio' => 'nullable|string|max:1000',
            'nationality' => 'nullable|string|max:100',
            'date_birth' => 'nullable|integer|min:1900|max:'.date('Y'),
            'city_id' => 'nullable|integer|exists:geo_cities,id',
            'lat' => 'nullable|numeric|between:-90,90',
            'lon' => 'nullable|numeric|between:-180,180',
            'avatar' => 'nullable|string|max:500',
            'media' => 'nullable|array',
            'working_hours' => 'nullable|array',
            'rating' => 'nullable|numeric|between:0,5',
        ];
    }
}
