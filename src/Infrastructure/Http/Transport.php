<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Http;

use Integra\Infrastructure\Generic\Result;

interface Transport
{
    public function response(Request $request): Result;
}
