<?php

namespace Integra\Infrastructure\Generic\Response;

use Integra\Infrastructure\Generic\Response;
use Integra\Infrastructure\Generic\Response\Pagination\NoPagination;

abstract class Successful implements Response
{
    private ?array $payload;
    private Pagination $pagination;
    private ?array $translated;

    public function __construct(array $payload = null, array $translated = [], Pagination $pagination = null,)
    {
        $this->payload = $payload;
        $this->pagination = $pagination ?? new NoPagination();
        $this->translated = $translated;
    }

    abstract public function code(): int;
    abstract public function defaultText(): string;

    public function isSuccessful(): bool
    {
        return true;
    }

    public function payload(): array
    {
        return $this->payload ?? [$this->defaultText()];
    }

    public function pagination(): Pagination
    {
        return $this->pagination;
    }

    public function translated(): array
    {
        return $this->translated;
    }
}
