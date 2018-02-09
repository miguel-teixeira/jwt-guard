<?php

namespace JwtGuard;

use Illuminate\Http\Request;


abstract class Token
{
    protected $signer;

    protected $builder;

    protected $parser;

    protected $secret = 'secret';

    protected $token;

    public function __construct()
    {
        $this->signer = $this->getSigner();

        $this->builder = $this->getBuilder();

        $this->parser = $this->getParser();
    }

    public function header(string $name): ?string
    {
        return $this->token->getHeader($name);
    }

    public function claim(string $name): ?string
    {
        return $this->token->getClaim($name);
    }

    public function setHeader(string $name, string $value): Token
    {
        $this->builder->setHeader($name, $value);

        return $this;
    }

    public function setClaim(string $name, string $value): Token
    {
        $this->builder->set($name, $value);

        return $this;
    }

    public function hasHeader(string $name): bool
    {
        return $this->token->hasHeader($name);
    }

    public function hasClaim(string $name): bool
    {
        return $this->token->hasClaim($name);
    }

    public function build(array $claims = []): Token
    {
        foreach ($claims as $name => $value) {
            $this->builder->set($name, $value);
        }

        $this->signToken();

        $this->token = $this->builder->getToken();

        return $this;
    }

    public function headers(): array
    {
        return $this->token->getHeaders();
    }

    public function claims(): array
    {
        return $this->token->getClaims();
    }

    public function payload(): string
    {
        return $this->token->getPayload();
    }

    public function isValid(): bool
    {
        return $this->token->validate($this->getValidationData())
            && $this->token->verify($this->signer, $this->secret);
    }

    public function setSecret(string $secret): Token
    {
        $this->secret = $secret;

        return $this;
    }

    public function encode(): string
    {
        return $this->token->__toString();
    }

    public function decode(string $token): Token
    {
        $this->token = $this->parser->parse((string) $token);

        return $this;
    }

    public function buildFromRequest(Request $request): Token
    {
        if (is_null(request()->bearerToken())) {

            return $this->emptyToken();
        }

        return $this->decode($request->bearerToken());
    }

    public function emptyToken(): Token
    {
        $this->builder = $this->getBuilder();

        $this->signToken();

        $this->token = $this->builder->getToken();

        return $this;
    }

    protected function signToken(): void
    {
        $this->builder->sign($this->signer, $this->secret);
    }

    abstract protected function getSigner();

    abstract protected function getBuilder();

    abstract protected function getParser();

    abstract protected function getValidationData();
}