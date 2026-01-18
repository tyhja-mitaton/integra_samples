<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http;

interface ExtendedRequest extends Request
{
    public function options(): array;
}
