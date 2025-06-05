<?php

declare(strict_types=1);

namespace App\Guards;

use App\Models\Organization;
use App\Models\User;
use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use stdClass;

use function Laravel\Prompts\error;

class AtlasGuard implements Guard
{
    protected UserProvider $provider;

    protected Request $request;

    protected $user = null;

    protected string $jwksUri = '/.well-known/jwks.json';

    protected $jwks;

    protected string $atlasEndpoint;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
        $this->atlasEndpoint = config('app.atlas_api_endpoint');
    }

    public function check()
    {
        return ! is_null($this->user());
    }

    public function guest()
    {
        return ! $this->check();
    }

    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $token = $this->getTokenFromRequest();

        if (! $token) {
            return null;
        }

        $this->loadJwks();
        $decodedToken = $this->decodeToken($token);

        if (! $decodedToken) {
            return null;
        }

        $user = $this->provider->retrieveById($decodedToken->sub);

        // since token is valid I can create user
        if (! $user) {
            $user = $this->createUser(/* $decodedToken->sub, $decodedToken->app_metadata->organization */);
        }

        $this->setUser($user);

        return $this->user;
    }

    public function login(Authenticatable $user) {}

    public function id(): int|string|null
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }

        return null;
    }

    public function validate(array $credentials = []): false
    {
        return false;
    }

    public function setUser(Authenticatable $user): void // @phpstan-ignore-line
    {
        $this->user = $user;
    }

    public function hasUser()
    {
        return true; // TODO to implement
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

    protected function getTokenFromRequest(): ?string
    {
        $tokenWithBearer = $this->request->header('Authorization', '');
        $jwt = mb_substr($tokenWithBearer, 7);

        if (empty($jwt)) {
            return null;
        }

        return $jwt;
    }

    protected function decodeToken(string $jwt): ?stdClass
    {
        $decoded = null;
        try {
            // TODO check if is valid for tests
            $decoded = JWT::decode($jwt, JWK::parseKeySet($this->jwks));
        } catch (Exception $e) {
            error($e->getMessage());
        } finally {
            return $decoded;
        }
    }

    protected function createUser()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => Str::of('Bearer ')->append($this->getTokenFromRequest()),
        ])->get($this->atlasEndpoint . '/api/auth/me');

        $userData = $response->object()->data;
        $organization = $this->createOrganization($userData->organization->id, $userData->organization->name);

        $user = new User([
            'name' => $userData->name,
            'email' => $userData->email,
            'atlas_member_id' => $userData->id,
        ]);

        $user->organization()->associate($organization);
        $user->save();

        return $user;
    }

    protected function createOrganization(string $organizationId, string $name): Organization
    {
        return Organization::firstOrCreate(
            ['atlas_organization_id' => $organizationId],
            ['name' => $name]
        );
    }
}
