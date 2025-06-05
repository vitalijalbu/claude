<?php

declare(strict_types=1);

namespace App\Atlas\Services;

use App\Atlas\Exceptions\GenericApiClientAtlasException;
use App\Atlas\Exceptions\MissingTokenAtlasException;
use Illuminate\Support\Facades\Http;

class ApiClient
{
    protected ?string $token = null;

    /**
     * Get member information
     */
    public function getMemberInfo(): object
    {
        if (! $this->token) {
            throw new MissingTokenAtlasException('Missing Token', 1);
        }

        $response = Http::asJson()->withToken($this->token)->get(config('atlas.base_url') . '/api/auth/me');
        if ($response->failed()) {
            throw new GenericApiClientAtlasException('Failed to get user info');
        }

        return $response->object()->data;
    }

    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }
}
