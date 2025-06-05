<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GrabberService
{
    private string $baseUrl;

    private int $defaultPerPage;

    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.internal.url') . '/api';
        $this->defaultPerPage = config('services.internal.per_page', 100);
        $this->timeout = config('services.internal.timeout', 30);
    }

    public function resolveJwt(): string
    {
        return Cache::remember('machine_jwt', 3600, function () {
            return config('services.internal.jwt_token', '-');
        });
    }

    /**
     * Fetch all data from a paginated endpoint
     */
    public function getList(string $endpoint, array $params = [], ?string $token = null): array
    {
        $url = $this->baseUrl . '/' . mb_ltrim($endpoint, '/');
        $page = 1;
        $allData = [];

        $params['per_page'] = $params['per_page'] ?? $this->defaultPerPage;

        do {
            $params['page'] = $page;

            $response = $this->makeHttpRequest($url, $params, $token);

            $data = $response->json();
            $pageData = $data['data'] ?? [];
            $meta = $data['meta'] ?? [];

            if (empty($pageData)) {
                break;
            }

            $allData = array_merge($allData, $pageData);

            $hasMore = isset($meta['current_page'], $meta['last_page'])
                && $meta['current_page'] < $meta['last_page'];

            $page++;
        } while ($hasMore);

        Log::info('Fetched ' . count($allData) . " items from: {$endpoint}");

        return $allData;
    }

    /**
     * Fetch a single item by ID
     */
    public function getOne(string $endpoint, string $id, array $params = [], ?string $token = null): array
    {
        $url = $this->baseUrl . '/' . mb_ltrim($endpoint, '/') . '/' . $id;

        $response = $this->makeHttpRequest($url, $params, $token);

        $data = $response->json();

        return $data['data'] ?? $data;
    }

    /**
     * Make HTTP request with authentication
     */
    private function makeHttpRequest(string $url, array $params = [], ?string $token = null)
    {
        $httpClient = Http::timeout($this->timeout);

        // Use provided token or fallback to configured token
        $authToken = $token ?? $this->resolveJwt();

        if ($authToken) {
            $httpClient = $httpClient->withHeaders([
                'Authorization' => 'Bearer ' . $authToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]);
        }

        $response = $httpClient->get($url, $params);

        if (! $response->ok()) {
            $errorBody = $response->body();
            Log::error('API request failed', [
                'url' => $url,
                'status' => $response->status(),
                'error' => $errorBody,
                'params' => $params,
            ]);

            throw new Exception('API Error: ' . $errorBody);
        }

        return $response;
    }
}
