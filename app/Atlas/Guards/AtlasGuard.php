<?php

declare(strict_types=1);

namespace App\Atlas\Guards;

use App\Atlas\Services\Authenticator;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class AtlasGuard implements Guard
{
    protected UserProvider $provider;

    protected Request $request;

    protected Authenticator $authenticator;

    protected $user = null;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->authenticator = new Authenticator();
        $this->provider = $provider;
        $this->request = $request;
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

        $user = $this->authenticator->setToken($token)->authenticate();

        if (! $user) {
            return null;
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

    public function setUser(Authenticatable $user): void
    {
        $this->user = $user;
    }

    public function hasUser()
    {
        return true; // TODO to implement
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
}
