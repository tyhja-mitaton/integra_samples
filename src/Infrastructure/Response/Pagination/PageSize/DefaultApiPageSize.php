<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Response\Pagination\PageSize;

use Integra\Infrastructure\Response\Pagination\PageSize;

class DefaultApiPageSize implements PageSize
{
    public function value(): int
    {
        return 20;
    }
}
