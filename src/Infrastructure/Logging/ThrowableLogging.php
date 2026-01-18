<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Logging;

use Throwable;

interface ThrowableLogging
{
    public function run(Throwable $throwable): void;
}
