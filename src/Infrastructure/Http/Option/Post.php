<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http\Option;

use GuzzleHttp\RequestOptions;
use Integra\Infrastructure\Http\Option;

class Post implements Option
{
    private array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function value(): array
    {
        return [RequestOptions::FORM_PARAMS => $this->params];
    }
}
