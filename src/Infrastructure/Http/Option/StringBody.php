<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http\Option;

use GuzzleHttp\RequestOptions;
use Integra\Infrastructure\Http\Option;

class StringBody implements Option
{
    private string $body;

    public function __construct(string $body)
    {
        $this->body = $body;
    }

    public function value(): array
    {
        return [RequestOptions::BODY => $this->body];
    }
}
