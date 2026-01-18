<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Console;

interface Message
{
    public function value(): string;
}
