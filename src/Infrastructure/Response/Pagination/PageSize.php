<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Response\Pagination;

interface PageSize
{
    public function value() : int;
}
