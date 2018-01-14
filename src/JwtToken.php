<?php

namespace JwtGuard;

use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;

class JwtToken extends Token
{
    protected function getSigner()
    {
        return new Sha256();
    }

    protected function getBuilder()
    {
        return new Builder();
    }

    protected function getParser()
    {
        return new Parser();
    }

    protected function getValidationData()
    {
        return new ValidationData();
    }
}