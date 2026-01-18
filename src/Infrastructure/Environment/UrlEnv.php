<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Environment;

class UrlEnv extends ExistedEnv
{
    public function value(): string
    {
        return rtrim($this->value, '/');
    }
}
