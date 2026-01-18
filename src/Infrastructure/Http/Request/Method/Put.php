<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http\Request\Method;

use Integra\Infrastructure\Http\Request\Method;

class Put implements Method
{
    public function value(): string
    {
        return 'PUT';
    }
}
