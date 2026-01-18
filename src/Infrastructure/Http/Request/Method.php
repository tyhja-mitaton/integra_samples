<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http\Request;

interface Method
{
    public function value(): string;
}
