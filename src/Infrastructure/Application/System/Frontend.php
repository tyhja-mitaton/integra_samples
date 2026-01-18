<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\System;

use Integra\Infrastructure\Application\System;

class Frontend implements System
{
    public function name(): string
    {
        return 'Frontend';
    }

    public function controllerNamespace(): string
    {
        return '\Integra\Controller\Frontend';
    }
}
