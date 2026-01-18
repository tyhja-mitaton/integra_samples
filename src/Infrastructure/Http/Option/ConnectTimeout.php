<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http\Option;

use GuzzleHttp\RequestOptions;
use Integra\Infrastructure\Http\Option;

class ConnectTimeout implements Option
{
    private int $seconds;

    public function __construct(int $seconds)
    {
        $this->seconds = $seconds;
    }

    public function value(): array
    {
        return [RequestOptions::CONNECT_TIMEOUT => $this->seconds];
    }
}
