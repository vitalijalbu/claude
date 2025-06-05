<?php

declare(strict_types=1);

namespace App\Atlas\Services;

use App\Atlas\DTOs\TokenPayload;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class Authenticator
{
    protected $jwks;

    protected string $token;

    protected string $atlasEndpoint;

    protected string $jwksUri = '/.well-known/jwks.json';

    protected mixed $memberInfo = null;

    protected UserService $userService;

    protected OrganizationService $organizationService;

    protected ApiClient $apiClient;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->organizationService = new OrganizationService();
        $this->apiClient = new ApiClient();
        $this->atlasEndpoint = config('atlas.base_url', '');
        $this->loadJwks();
    }

    /**
     * Autentica un utente basato sul token di Atlas, creando risorse se necessario.
     */
    public function authenticate(): ?User
    {
        $verifiedToken = $this->decodeToken($this->token);

        if (! $verifiedToken) {
            Log::warning('Invalid or expired Atlas token provided.');

            return null;
        }

        $organization = $this->organizationService->resolveOrganizationFromToken($verifiedToken);

        if (! $organization) {
            $organization = $this->organizationService->createOrganization([
                'name' => $this->getMemberInfo()->organization->name,
                'atlas_organization_id' => $this->memberInfo->organization->id,
            ]);
        }

        $user = User::where('atlas_member_id', $verifiedToken->sub)->first();

        if (! $user) {
            $user = $this->userService->createUser([
                'name' => $this->getMemberInfo()->name,
                'email' => $this->getMemberInfo()->email,
                'atlas_member_id' => $this->getMemberInfo()->id,
                'role_codes' => collect($this->getMemberInfo()->roles)->pluck('code')->toArray(),
            ], $organization);
        } elseif ($user->updated_at->greaterThan(Carbon::now()->subHours(24))) {
            $user = $this->userService->updateUser($user, [
                'name' => $this->getMemberInfo()->name,
                'email' => $this->getMemberInfo()->email,
                'role_codes' => collect($this->getMemberInfo()->roles)->pluck('code')->toArray(),
            ]);
        }

        $user->load(['roles', 'organization']);

        return $user;
    }

    /**
     * Verifica solo la validità di base del token Atlas (es. formato, non scaduto).
     * Non autentica un utente specifico.
     */
    public function isValidToken(): bool
    {
        try {
            return (bool) $this->decodeToken($this->token);
        } catch (Exception $e) {
            Log::warning("Atlas token validation failed: {$e->getMessage()}");

            return false;
        }
    }

    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    protected function loadJwks(): void
    {
        $this->jwks = Cache::get('atlas-jwks');

        if (isset($this->jwks)) {
            return;
        }

        $response = Http::get($this->atlasEndpoint . $this->jwksUri);

        $this->jwks = $response->json();
        Cache::put('atlas-jwks', $this->jwks, 60 * 5);
    }

    private function getMemberInfo()
    {
        if ($this->memberInfo === null) {
            $this->memberInfo = $this->apiClient->setToken($this->token)->getMemberInfo();
        }

        return $this->memberInfo;
    }

    private function decodeToken(): ?TokenPayload
    {
        try {
            // TODO check if is valid for tests
            $decoded = JWT::decode($this->token, JWK::parseKeySet($this->jwks));

            // verify the issuer is correct
            if ($decoded->iss !== config('atlas.jwt_issuer')) {
                throw new InvalidArgumentException('Invalid token issuer.');
            }

            // TBI: validate app audience (eg. idf:portal:dam)
            if (! in_array(config('atlas.jwt_app_audience'), (array) $decoded->aud)) {
                throw new InvalidArgumentException('Invalid token audience.');
            }

            return new TokenPayload((array) $decoded);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return null;
        }
    }
}
