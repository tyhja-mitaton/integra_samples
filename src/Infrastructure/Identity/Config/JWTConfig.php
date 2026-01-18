<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Identity\Config;

use bizley\jwt\Jwt;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Integra\Infrastructure\Application\Config;
use Integra\Infrastructure\Environment\Env;

class JWTConfig implements Config
{
    public function value(): array
    {
        return [
            'class' => Jwt::class,
            'signer' => Jwt::HS256,
            'signingKey' => [
                'key' => (new Env('UB_JWT_SECRET'))->value(),
                'method' => Jwt::METHOD_PLAIN,
            ],
            'verifyingKey' => [
                'key' => (new Env('UB_JWT_SECRET'))->value(),
                'method' => Jwt::METHOD_PLAIN,
            ],
            'validationConstraints' => function (Jwt $jwt) {
                $config = $jwt->getConfiguration();
                return [
                    new SignedWith($config->signer(), $config->verificationKey()),
                    new LooseValidAt(new SystemClock(new \DateTimeZone('UTC')))
                ];
            },
        ];
    }
}
