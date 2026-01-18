<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Console;

use Integra\Infrastructure\Generic\Result;

interface Command
{
    public function run(): Result;
}
