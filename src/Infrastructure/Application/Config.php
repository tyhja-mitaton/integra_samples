<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application;

interface Config
{
    public function value(): array;
}
