<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Generic;

use Integra\Infrastructure\Generic\Response\Pagination;

interface Response
{
    public function code(): int;
    public function isSuccessful(): bool;
    public function payload(): array;
    public function pagination(): Pagination;
    public function translated(): array;
}
