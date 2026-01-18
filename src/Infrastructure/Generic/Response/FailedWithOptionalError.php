<?php

namespace Integra\Infrastructure\Generic\Response;

use Integra\Infrastructure\Generic\Response;
use Integra\Infrastructure\Generic\Response\Pagination\NoPagination;

abstract class FailedWithOptionalError implements Response
{
    protected ?array $error;
    protected ?array $translated;

    public function __construct(array $error = null, array $translated = [])
    {
        $this->error = $error;
        $this->translated = $translated;
    }

    abstract public function code(): int;
    abstract public function defaultErrorText(): string;

    public function isSuccessful(): bool
    {
        return false;
    }

    public function payload(): array
    {
        return $this->error ?? [$this->defaultErrorText()];
    }

    public function translated(): array
    {
        return $this->translated;
    }

    public function pagination(): Pagination
    {
        return new NoPagination();
    }
}
