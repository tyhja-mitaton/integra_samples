<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http\Request;

interface Url
{
    public function value(): string;
}
