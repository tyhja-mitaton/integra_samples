<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Response;

use Throwable;
use Integra\Infrastructure\Generic\Response;
use Integra\Infrastructure\Generic\Response\Pagination;
use Integra\Infrastructure\Generic\Response\Pagination\NoPagination;

abstract class FromThrowable implements Response
{
    protected Throwable $throwable;

    public function __construct(Throwable $throwable)
    {
        $this->throwable = $throwable;
    }

    public function code(): int
    {
        return $this->throwable->statusCode ?? 500;
    }

    public function isSuccessful(): bool
    {
        return false;
    }

    public function pagination(): Pagination
    {
        return new NoPagination();
    }

    public function translated(): array
    {
        return [];
    }

    abstract public function payload(): array;
}
