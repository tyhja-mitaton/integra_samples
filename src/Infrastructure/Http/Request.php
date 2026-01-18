<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http;

use Integra\Infrastructure\Http\Request\Method;
use Integra\Infrastructure\Http\Request\Url;

interface Request extends MinimalRequest
{
    public function method(): Method;
    public function url(): Url;
}
