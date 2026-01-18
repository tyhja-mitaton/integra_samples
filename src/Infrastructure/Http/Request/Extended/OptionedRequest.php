<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http\Request\Extended;

use Integra\Infrastructure\Http\ExtendedRequest;
use Integra\Infrastructure\Http\Option;
use Integra\Infrastructure\Http\Request\Method;
use Integra\Infrastructure\Http\Request\Url;

class OptionedRequest implements ExtendedRequest
{
    private Method $method;
    private Url $url;
    private array $options;

    public function __construct(Method $method, Url $url, Option ...$options)
    {
        $this->method = $method;
        $this->url = $url;
        $this->options = $options;
    }

    /** @return Option[] */
    public function options(): array
    {
        return $this->options;
    }

    public function headers(): array
    {
        return [];
    }

    public function body(): string
    {
        return '';
    }

    public function method(): Method
    {
        return $this->method;
    }

    public function url(): Url
    {
        return $this->url;
    }
}
