<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Environment;

class Env extends ExistedEnv
{
    public function value(): string
    {
        return $this->value;
    }
}
