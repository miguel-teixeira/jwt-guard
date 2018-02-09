<?php

namespace JwtGuard;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class JwtGuard implements Guard
{
    use GuardHelpers;

    protected $request;

    protected $token;

    public function __construct(UserProvider $provider, Token $token, $request = null)
    {
        $this->provider = $provider;

        $this->token = $token;

        $this->request = $request;
    }

    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        if ($this->token->hasClaim('uid') && $this->token->isValid())
        {
            $this->loginUsingId($this->token->claim('uid'));

            return $this->user;
        }

        return null;
    }

    public function validate(array $credentials = []): bool
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        return $this->hasValidCredentials($user, $credentials);
    }

    public function attempt(array $credentials = []): bool
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user);

            return true;
        }

        return false;
    }

    public function login(Authenticatable $user): void
    {
        $this->setUser($user);
    }

    public function loginUsingId($id): void
    {
        $this->login($this->provider->retrieveById($id));
    }

    public function logout(): void
    {
        $this->user = null;

        $this->token->emptyToken();
    }

    protected function hasValidCredentials(Authenticatable $user, array $credentials): bool
    {
        return ! is_null($user)
            && $this->provider->validateCredentials($user, $credentials);
    }
}