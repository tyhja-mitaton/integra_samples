<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Application\System;

use Integra\Infrastructure\Application\System;

class Common implements System
{
    public function name(): string
    {
        return 'Common';
    }

    public function controllerNamespace(): string
    {
        return '\Integra\Controller\Common';
    }
}
