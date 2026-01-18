<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\Routes;

use Integra\Infrastructure\Application\Routes;

class EmptyRoutes implements Routes
{
    public function value(): array
    {
        return [];
    }
}
